<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;
use App\Support\MediaPath;

class AdminRegistrationController extends Controller
{
    public function create(): View
    {
        return view('admin-register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:admins,username'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'username.unique' => 'This username is already taken. Please choose another username.',
        ]);

        $username = $validated['username'] ?? null;
        $name = $validated['name'];
        
        // Generate email: use username if it looks like one, or name-based fallback
        if ($username && filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $email = $username;
        } else {
            $base = $username ?: Str::slug($name);
            $email = strtolower($base) . '@' . request()->getHost();
        }

        // Ensure email is unique
        if (\App\Models\Admin::where('email', $email)->exists()) {
             $email = strtolower($username ?: Str::slug($name)) . '.' . time() . '@' . request()->getHost();
        }

        // Ensure username is not null for the database if not provided in the request
        if (empty($username)) {
            $username = Str::slug($name);
            // Check for collision on the generated username
            if (\App\Models\Admin::where('username', $username)->exists()) {
                $username = $username . '.' . time();
            }
        }

        $adminData = [
            'email' => $email,
            'username' => $username,
            'password' => $validated['password'],
        ];

        if (Schema::hasColumn('admins', 'contact_number')) {
            $adminData['contact_number'] = $validated['contact_number'] ?? null;
        }

        // Defensively handle different possible name column schemas
        if (Schema::hasColumn('admins', 'name')) {
            $adminData['name'] = $name;
        }

        $nameParts = explode(' ', $name, 2);
        if (Schema::hasColumn('admins', 'first_name')) $adminData['first_name'] = $nameParts[0];
        if (Schema::hasColumn('admins', 'last_name')) $adminData['last_name'] = $nameParts[1] ?? '';

        // Add defensive check for vendor_request_message
        if (Schema::hasColumn('admins', 'vendor_request_message')) {
            $adminData['vendor_request_message'] = ''; // Provide an empty string as a default
        }
        $admin = \App\Models\Admin::create($adminData);

        Auth::guard('admin')->login($admin);

        return redirect()
            ->route('admin.dashboard')
            ->with('status', 'Admin account created successfully.');
    }

    public function dashboard(): View
    {
        $admin = Auth::guard('admin')->user();
        return view('admin-dashboard', $this->dashboardViewData($admin));
    }

    public function choosePropertyType(): View
    {
        $user = Auth::guard('admin')->user();
        $commercialCount = 0;
        $residentialCount = 0;

        $connection = $this->resolvePropertiesConnection();
        if ($connection && Schema::connection($connection)->hasTable('properties')) {
            $commercialCount = DB::connection($connection)->table('properties')
                ->where('type', 'commercial')
                ->count();
            $residentialCount = DB::connection($connection)->table('properties')
                ->where('type', 'residential')
                ->count();
        }

        return view('admin-dashboard', array_merge(
            $this->dashboardViewData($user),
            [
                'currentPage' => 'choose-property-type',
                'currentGroup' => 'property-management',
                'currentItem' => [
                    'label' => 'Choose Property Type',
                    'slug' => 'choose-property-type',
                    'group' => 'property-management',
                ],
                'commercialCount' => $commercialCount,
                'residentialCount' => $residentialCount,
            ]
        ));
    }

    public function createPropertyForm(string $type): View
    {
        $user = Auth::guard('admin')->user();
        $normalizedType = Str::lower($type);

        abort_unless(in_array($normalizedType, ['residential', 'commercial'], true), 404);

        $connection = $this->resolvePropertiesConnection();
        $topPicksCategories = [];
        if ($connection && Schema::connection($connection)->hasTable('top_picks')) {
            $topPicksCategories = DB::connection($connection)->table('top_picks')
                ->where('status', 1)
                ->get();
        }

        return view('admin-dashboard', array_merge(
            $this->dashboardViewData($user),
            [
                'currentPage' => 'add-property',
                'currentGroup' => 'property-management',
                'currentItem' => [
                    'label' => ucfirst($normalizedType) . ' Property',
                    'slug' => 'add-property',
                    'group' => 'property-management',
                ],
                'selectedPropertyType' => $normalizedType,
                'countries' => \App\Models\Country::orderBy('name')->get(),
                'states' => \App\Models\State::with('country')->orderBy('name')->get(),
                'cities' => \App\Models\City::with(['country', 'state'])->orderBy('name')->get(),
                'propertyPlaces' => \App\Models\PropertyPlace::orderBy('name')->get(),
                'amenities' => \App\Models\Amenity::orderBy('serial_number')->get(),
                'categories' => \App\Models\Category::orderBy('serial_number')->get(),
                'topPicksCategories' => $topPicksCategories,
            ]
        ));
    }

    public function storeProperty(Request $request): RedirectResponse
    {
        $connection = $this->resolvePropertiesConnection();
        if (! $connection || ! Schema::connection($connection)->hasTable('properties')) {
            return redirect()
                ->route('admin.section', ['section' => 'manage-properties'])
                ->with('status', 'Property table not found.');
        }

        $validated = $this->validatePropertyPayload($request);
        $locationName = \App\Models\PropertyPlace::where('id', $validated['property_place_id'])->value('name');
        $mainImagePath = $request->file('display_image')?->store('properties/display', 'public');
        $brochurePath = $request->file('brochure')?->store('properties/brochures', 'public');

        $hasBhkColumn = Schema::connection($connection)->hasColumn('properties', 'bhk');

        $propertyPayload = [
            'property_name' => $validated['property_name'],
            'category_id' => $validated['category_id'],
            'full_address' => $validated['full_address'],
            'min_price' => $validated['min_price'] ?? null,
            'max_price' => $validated['max_price'] ?? null,
            'vendor_id' => 0,
            'brochure' => $brochurePath,
            'type' => $validated['property_type'],
            'property_area' => $locationName ?: null,
            'area' => $validated['area'] ?? null,
            'min_area' => $validated['min_area'] ?? null,
            'max_area' => $validated['max_area'] ?? null,
            'country' => \App\Models\Country::where('id', $validated['country_id'])->value('name'),
            'state' => \App\Models\State::where('id', $validated['state_id'])->value('name'),
            'city' => \App\Models\City::where('id', $validated['city_id'])->value('name'),
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'pincode' => $validated['pincode'],
            'overview' => $validated['overview'],
            'highlights' => $validated['highlights'],
            'about_project' => $validated['about_project'],
            'construction_status' => $validated['construction_status'],
            'possession_date' => $validated['possession_date'] ?? null,
            'furnished_status' => $this->normalizeFurnishedStatus($validated['furnished_status']),
            'main_property_image' => $mainImagePath,
            'display_image' => $mainImagePath,
            'status' => (int) $validated['status'],
            'approve_status' => 2,
            'new_launched' => ($validated['new_launched'] ?? null) === 'yes' ? 'yes' : 'no',
            'elite_project' => ($validated['elite_project'] ?? null) === 'yes' ? 'yes' : 'no',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if ($hasBhkColumn) {
            $propertyPayload['bhk'] = !empty($validated['bhk'])
                ? json_encode(array_values($validated['bhk']), JSON_UNESCAPED_UNICODE)
                : null;
        }

        $propertyId = DB::connection($connection)->table('properties')->insertGetId($propertyPayload);

        $this->syncPropertyExtraData($connection, $propertyId, $request, $validated);

        return redirect()
            ->route('admin.section', ['section' => 'manage-properties'])
            ->with('status', 'Property added successfully.');
    }

    public function editProperty(int $id): View
    {
        $user = Auth::guard('admin')->user();
        $connection = $this->resolvePropertiesConnection();
        $property = null;
        $propertyContent = null;
        $selectedAmenities = [];
        $propertyFaqs = [];
        $selectedTopPicks = [];
        $topPicksCategories = [];
        $propertyCountryId = null;
        $propertyStateId = null;
        $propertyCityId = null;
        $propertyPlaceId = null;
        if ($connection && Schema::connection($connection)->hasTable('properties')) {
            $property = DB::connection($connection)->table('properties')->where('id', $id)->first();
            if ($property) {
                $propertyCountryId = \App\Models\Country::where('name', $property->country)->value('id');
                $propertyStateId = \App\Models\State::where('name', $property->state)->value('id');
                $propertyCityId = \App\Models\City::where('name', $property->city)->value('id');
                $propertyPlaceId = \App\Models\PropertyPlace::where('name', $property->property_area)->value('id');
            }
            if ($property && Schema::connection($connection)->hasTable('property_amenities')) {
                $selectedAmenities = DB::connection($connection)->table('property_amenities')
                    ->where('property_id', $id)
                    ->pluck('amenity_id')
                    ->map(fn ($amenityId) => (int) $amenityId)
                    ->all();
            }
            if ($property && Schema::connection($connection)->hasTable('property_faq')) {
                $propertyFaqs = DB::connection($connection)->table('property_faq')
                    ->where('property_id', $id)
                    ->select('question', 'answer')
                    ->orderBy('id')
                    ->limit(25)
                    ->get()
                    ->toArray();
            }
            if ($property && Schema::connection($connection)->hasTable('property_contents')) {
                $propertyContent = DB::connection($connection)->table('property_contents')
                    ->where('property_id', $id)
                    ->orderByDesc('id')
                    ->first();
            }
            if ($property && Schema::connection($connection)->hasTable('property_top_picks')) {
                $selectedTopPicks = DB::connection($connection)->table('property_top_picks')
                    ->where('property_id', $id)
                    ->pluck('top_picks_id')
                    ->map(fn ($tpId) => (int) $tpId)
                    ->all();
            }
            if ($connection && Schema::connection($connection)->hasTable('top_picks')) {
                $topPicksCategories = DB::connection($connection)->table('top_picks')
                    ->where('status', 1)
                    ->get();
            }
        }

        abort_if(!$property, 404);

        return view('admin-dashboard', array_merge(
            $this->dashboardViewData($user),
            [
                'currentPage' => 'add-property',
                'currentGroup' => 'property-management',
                'currentItem' => [
                    'label' => 'Edit Property',
                    'slug' => 'manage-properties',
                    'group' => 'property-management',
                ],
                'property' => $property,
                'propertyContent' => $propertyContent,
                'selectedAmenities' => $selectedAmenities,
                'propertyFaqs' => $propertyFaqs,
                'selectedTopPicks' => $selectedTopPicks,
                'topPicksCategories' => $topPicksCategories,
                'propertyCountryId' => $propertyCountryId,
                'propertyStateId' => $propertyStateId,
                'propertyCityId' => $propertyCityId,
                'propertyPlaceId' => $propertyPlaceId,
                'mode' => 'edit',
                'selectedPropertyType' => strtolower($property->type ?? 'residential'),
                'countries' => \App\Models\Country::orderBy('name')->get(),
                'states' => \App\Models\State::with('country')->orderBy('name')->get(),
                'cities' => \App\Models\City::with(['country', 'state'])->orderBy('name')->get(),
                'propertyPlaces' => \App\Models\PropertyPlace::orderBy('name')->get(),
                'amenities' => \App\Models\Amenity::orderBy('serial_number')->get(),
                'categories' => \App\Models\Category::orderBy('serial_number')->get(),
                'topPicksCategories' => $topPicksCategories,
            ]
        ));
    }

    public function destroyProperty(int $id): RedirectResponse
    {
        $connection = $this->resolvePropertiesConnection();
        if ($connection && Schema::connection($connection)->hasTable('properties')) {
            DB::connection($connection)->table('properties')->where('id', $id)->delete();
            return redirect()
                ->route('admin.section', ['section' => 'manage-properties'])
                ->with('status', 'Property deleted successfully.');
        }

        return redirect()
            ->route('admin.section', ['section' => 'manage-properties'])
            ->with('status', 'Property table not found.');
    }

    public function updateProperty(Request $request, int $id): RedirectResponse
    {
        $connection = $this->resolvePropertiesConnection();
        if ($connection && Schema::connection($connection)->hasTable('properties')) {
            $validated = $this->validatePropertyPayload($request, true);
            $existing = DB::connection($connection)->table('properties')->where('id', $id)->first();
            abort_if(!$existing, 404);

            $locationName = \App\Models\PropertyPlace::where('id', $validated['property_place_id'])->value('name');
            $mainImagePath = $existing->main_property_image;
            if ($request->hasFile('display_image')) {
                $mainImagePath = $request->file('display_image')->store('properties/display', 'public');
                $this->deleteUploadedFile($existing->main_property_image ?? null);
            }

            $brochurePath = $existing->brochure;
            if ($request->hasFile('brochure')) {
                $brochurePath = $request->file('brochure')->store('properties/brochures', 'public');
                $this->deleteUploadedFile($existing->brochure ?? null);
            }

            DB::connection($connection)->table('properties')->where('id', $id)->update([
                'property_name' => $validated['property_name'],
                'category_id' => $validated['category_id'],
                'full_address' => $validated['full_address'],
                'min_price' => $validated['min_price'] ?? null,
                'max_price' => $validated['max_price'] ?? null,
                'brochure' => $brochurePath,
                'type' => $validated['property_type'],
                'property_area' => $locationName ?: null,
                'area' => $validated['area'] ?? null,
                'min_area' => $validated['min_area'] ?? null,
                'max_area' => $validated['max_area'] ?? null,
                'country' => \App\Models\Country::where('id', $validated['country_id'])->value('name'),
                'state' => \App\Models\State::where('id', $validated['state_id'])->value('name'),
                'city' => \App\Models\City::where('id', $validated['city_id'])->value('name'),
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'pincode' => $validated['pincode'],
                'overview' => $validated['overview'],
                'highlights' => $validated['highlights'],
                'about_project' => $validated['about_project'],
                'construction_status' => $validated['construction_status'],
                'possession_date' => $validated['possession_date'] ?? null,
                'bhk' => !empty($validated['bhk']) ? json_encode(array_values($validated['bhk']), JSON_UNESCAPED_UNICODE) : null,
                'furnished_status' => $this->normalizeFurnishedStatus($validated['furnished_status']),
                'main_property_image' => $mainImagePath,
                'display_image' => $mainImagePath,
                'status' => (int) $validated['status'],
                'new_launched' => ($validated['new_launched'] ?? null) === 'yes' ? 'yes' : 'no',
                'elite_project' => ($validated['elite_project'] ?? null) === 'yes' ? 'yes' : 'no',
                'updated_at' => now(),
            ]);

            $this->syncPropertyExtraData($connection, $id, $request, $validated, true);

            return redirect()
                ->route('admin.section', ['section' => 'manage-properties'])
                ->with('status', 'Property updated successfully.');
        }

        return redirect()->back()->with('status', 'Could not update property.');
    }

    public function updatePropertyApprovalStatus(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'approve_status' => ['required', 'in:0,1,2'],
        ]);

        $connection = $this->resolvePropertiesConnection();
        if (! $connection || ! Schema::connection($connection)->hasTable('properties')) {
            return redirect()
                ->route('admin.section', ['section' => 'manage-properties'])
                ->with('status', 'Property table not found.');
        }

        $property = DB::connection($connection)->table('properties')->where('id', $id)->first();
        abort_if(! $property, 404);

        $payload = ['approve_status' => (int) $validated['approve_status']];
        if (Schema::connection($connection)->hasColumn('properties', 'updated_at')) {
            $payload['updated_at'] = now();
        }

        DB::connection($connection)->table('properties')->where('id', $id)->update($payload);

        return redirect()
            ->route('admin.section', ['section' => 'manage-properties'])
            ->with('status', 'Property approval status updated successfully.');
    }

    private function validatePropertyPayload(Request $request, bool $isEdit = false): array
    {
        $displayImageRule = $isEdit ? ['nullable', 'image', 'mimes:png,jpg,jpeg,avif', 'max:2048'] : ['required', 'image', 'mimes:png,jpg,jpeg,avif', 'max:2048'];
        $brochureRule = $isEdit ? ['nullable', 'file', 'mimes:pdf', 'max:10240'] : ['required', 'file', 'mimes:pdf', 'max:10240'];
        $galleryRule = $isEdit ? ['nullable', 'array'] : ['required', 'array', 'min:1'];

        $validated = $request->validate([
            'property_name' => ['required', 'string', 'max:255'],
            'property_type' => ['required', 'in:residential,commercial'],
            'full_address' => ['required', 'string', 'max:255'],
            'country_id' => ['required', 'exists:property_countries,id'],
            'state_id' => ['required', 'exists:property_states,id'],
            'city_id' => ['required', 'exists:property_cities,id'],
            'property_place_id' => ['required', 'exists:property_places,id'],
            'pincode' => ['required', 'string', 'max:15'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'map_selected' => ['nullable', 'string', 'max:500'],
            'amenity_ids' => ['required', 'array', 'min:1'],
            'amenity_ids.*' => ['integer', 'exists:amenities,id'],
            'top_picks' => ['nullable', 'array'],
            'elite_project' => ['nullable', 'in:yes'],
            'new_launched' => ['nullable', 'in:yes'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            'bhk' => ['nullable', 'array'],
            'bhk.*' => ['string', 'in:1 BHK,2 BHK,3 BHK,4 BHK,5+ BHK'],
            'construction_status' => ['required', 'in:Ready to Move,Under Construction,New Launch'],
            'possession_date' => ['nullable', 'date'],
            'furnished_status' => ['required', 'in:Furnished,Semi-furnished,Unfurnished'],
            'area' => ['nullable', 'integer', 'min:1'],
            'min_area' => ['nullable', 'integer', 'min:1'],
            'max_area' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:1,0'],
            'category_id' => ['required', 'exists:property_categories,id'],
            'overview' => ['required', 'string'],
            'highlights' => ['required', 'string'],
            'about_project' => ['required', 'string'],
            'rera_number' => ['nullable', 'string', 'max:255'],
            'display_image' => $displayImageRule,
            'brochure' => $brochureRule,
            'gallery_images' => $galleryRule,
            'gallery_images.*' => ['image', 'mimes:png,jpg,jpeg,avif', 'max:2048'],
            'floor_plan_images' => ['nullable', 'array'],
            'floor_plan_images.*' => ['image', 'mimes:png,jpg,jpeg,avif', 'max:2048'],
            'faqs' => ['nullable', 'array', 'max:25'],
            'faqs.*.question' => ['nullable', 'string', 'max:1000'],
            'faqs.*.answer' => ['nullable', 'string', 'max:5000'],
        ], [
            'amenity_ids.required' => 'Please select at least one amenity.',
            'gallery_images.required' => 'At least one gallery image is required.',
            'brochure.mimes' => 'Brochure must be a PDF file.',
        ]);

        if (empty($validated['min_price']) && empty($validated['max_price'])) {
            throw ValidationException::withMessages([
                'min_price' => 'Please provide at least one value for Min Price or Max Price.',
            ]);
        }

        if (empty($validated['area']) && empty($validated['min_area']) && empty($validated['max_area'])) {
            throw ValidationException::withMessages([
                'area' => 'Please provide Area, Min Area, or Max Area.',
            ]);
        }

        return $validated;
    }

    private function syncPropertyExtraData(string $connection, int $propertyId, Request $request, array $validated, bool $isUpdate = false): void
    {
        if (Schema::connection($connection)->hasTable('property_amenities')) {
            DB::connection($connection)->table('property_amenities')->where('property_id', $propertyId)->delete();
            $amenityRows = collect($validated['amenity_ids'] ?? [])
                ->unique()
                ->map(fn ($amenityId) => [
                    'property_id' => $propertyId,
                    'amenity_id' => $amenityId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->values()->all();
            if (!empty($amenityRows)) {
                DB::connection($connection)->table('property_amenities')->insert($amenityRows);
            }
        }

        if (Schema::connection($connection)->hasTable('property_slider_images')) {
            $hasGalleryUploads = $request->hasFile('gallery_images');
            if ($isUpdate && $hasGalleryUploads) {
                DB::connection($connection)->table('property_slider_images')->where('property_id', $propertyId)->delete();
            }

            $galleryRows = [];
            foreach ($request->file('gallery_images', []) as $galleryImage) {
                $galleryRows[] = [
                    'property_id' => $propertyId,
                    'image' => $galleryImage->store('properties/gallery', 'public'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (!empty($galleryRows)) {
                DB::connection($connection)->table('property_slider_images')->insert($galleryRows);
            }
        }

        if (Schema::connection($connection)->hasTable('property_floor_plan')) {
            $hasFloorPlanUploads = $request->hasFile('floor_plan_images');
            if ($isUpdate && $hasFloorPlanUploads) {
                DB::connection($connection)->table('property_floor_plan')->where('property_id', $propertyId)->delete();
            }
            $floorRows = [];
            $floorFirst = null;
            foreach ($request->file('floor_plan_images', []) as $floorPlanImage) {
                $stored = $floorPlanImage->store('properties/floor-plans', 'public');
                $floorFirst ??= $stored;
                $floorRows[] = [
                    'property_id' => $propertyId,
                    'file' => $stored,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (!empty($floorRows)) {
                DB::connection($connection)->table('property_floor_plan')->insert($floorRows);
            }
            if ($floorFirst) {
                DB::connection($connection)->table('properties')->where('id', $propertyId)->update(['floor_plan_image' => $floorFirst]);
            }
        }

        if (Schema::connection($connection)->hasTable('property_brochures')) {
            DB::connection($connection)->table('property_brochures')->where('property_id', $propertyId)->delete();
            $brochureFile = DB::connection($connection)->table('properties')->where('id', $propertyId)->value('brochure');
            if ($brochureFile) {
                DB::connection($connection)->table('property_brochures')->insert([
                    'property_id' => $propertyId,
                    'file' => $brochureFile,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        if (Schema::connection($connection)->hasTable('property_faq')) {
            DB::connection($connection)->table('property_faq')->where('property_id', $propertyId)->delete();
            $faqRows = collect($validated['faqs'] ?? [])
                ->filter(fn ($faq) => filled($faq['question'] ?? null) && filled($faq['answer'] ?? null))
                ->take(25)
                ->map(fn ($faq) => [
                    'property_id' => $propertyId,
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->values()->all();
            if (!empty($faqRows)) {
                DB::connection($connection)->table('property_faq')->insert($faqRows);
            }
        }

        if (Schema::connection($connection)->hasTable('property_contents')) {
            $languageId = $this->resolveDefaultLanguageId($connection);
            if ($languageId) {
                $title = $validated['property_name'];
                $slug = Str::slug($title) . '-' . $propertyId;
                $payload = [
                    'property_id' => $propertyId,
                    'language_id' => $languageId,
                    'title' => $title,
                    'slug' => $slug,
                    'address' => $validated['full_address'],
                    'description' => $validated['overview'],
                    'meta_keyword' => $validated['rera_number'] ?? null,
                    'meta_description' => $validated['map_selected'] ?? null,
                    'updated_at' => now(),
                ];

                $existing = DB::connection($connection)->table('property_contents')
                    ->where('property_id', $propertyId)
                    ->where('language_id', $languageId)
                    ->first();

                if ($existing) {
                    DB::connection($connection)->table('property_contents')
                        ->where('id', $existing->id)
                        ->update($payload);
                } else {
                    DB::connection($connection)->table('property_contents')
                        ->insert($payload + ['created_at' => now()]);
                }
            }
        }

        if (Schema::connection($connection)->hasTable('property_top_picks')) {
            DB::connection($connection)->table('property_top_picks')->where('property_id', $propertyId)->delete();
            $topPickIds = $validated['top_picks'] ?? [];
            if (!empty($topPickIds) && is_array($topPickIds)) {
                $topPickRows = collect($topPickIds)
                    ->map(fn ($tpId) => [
                        'property_id' => $propertyId,
                        'top_picks_id' => (int) $tpId,
                        'created_date' => now(),
                        'updated_date' => now(),
                    ])->values()->all();
                DB::connection($connection)->table('property_top_picks')->insert($topPickRows);
            }
        }
    }

    private function resolveDefaultLanguageId(string $connection): ?int
    {
        if (! Schema::connection($connection)->hasTable('languages')) {
            return null;
        }

        $languageId = DB::connection($connection)->table('languages')->where('is_default', 1)->value('id');
        if ($languageId) {
            return (int) $languageId;
        }

        $fallbackLanguageId = DB::connection($connection)->table('languages')->orderBy('id')->value('id');
        return $fallbackLanguageId ? (int) $fallbackLanguageId : null;
    }

    private function normalizeFurnishedStatus(string $value): string
    {
        if ($value === 'Semi Furnished') {
            return 'Semi-furnished';
        }

        return $value;
    }

    private function deleteUploadedFile(?string $path): void
    {
        if (filled($path) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function enquiryAnalysis(): View
    {
        $user = Auth::guard('admin')->user();

        $labels = [];
        $callData = [];
        $whatsappData = [];
        $propertyData = [];

        $hasInteractionsTable = Schema::hasTable('enquiry_interactions');

        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $date = $day->format('Y-m-d');
            $labels[] = $day->format('M d');

            $callData[] = $hasInteractionsTable
                ? \App\Models\EnquiryInteraction::where('type', 'call')->whereDate('created_at', $date)->count()
                : 0;

            $whatsappData[] = $hasInteractionsTable
                ? \App\Models\EnquiryInteraction::where('type', 'whatsapp')->whereDate('created_at', $date)->count()
                : 0;

            $propertyData[] = \App\Models\PropertyEnquiry::whereDate('created_at', $date)->count();
        }

        $statusCounts = \App\Models\PropertyEnquiry::selectRaw('enquiry_status, count(*) as total')
            ->groupBy('enquiry_status')
            ->pluck('total', 'enquiry_status')
            ->toArray();

        return view('admin-dashboard', array_merge(
            $this->dashboardViewData($user),
            [
                'currentPage' => 'enquiry-analysis',
                'currentGroup' => 'enquiry-management',
                'chartLabels' => $labels,
                'callData' => $callData,
                'whatsappData' => $whatsappData,
                'propertyData' => $propertyData,
                'statusCounts' => $statusCounts,
                'totalCalls' => array_sum($callData),
                'totalWhatsapp' => array_sum($whatsappData),
                'totalProperty' => array_sum($propertyData),
            ]
        ));
    }

    public function storeEnquiryInteraction(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:call,whatsapp',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        \App\Models\EnquiryInteraction::create($validated);

        return redirect()->back()->with('status', 'Enquiry recorded successfully.');
    }

    public function section(string $section): View
    {
        $user = Auth::guard('admin')->user();

        if ($section === 'property-enquiry') {
            $connection = $this->resolvePropertiesConnection();
            $enquiries = \App\Models\PropertyEnquiry::latest()->paginate(10);
            
            // Optionally attach property names if connection is valid
            if ($connection) {
                foreach ($enquiries as $enq) {
                    $prop = \DB::connection($connection)->table('properties')->where('id', $enq->property_id)->first();
                    $enq->property_name = $prop->property_name ?? 'N/A';
                }
            }

            return view('admin-dashboard', array_merge(
                $this->dashboardViewData($user),
                [
                    'currentPage' => 'property-enquiry',
                    'currentGroup' => 'enquiry-management',
                    'enquiries' => $enquiries
                ]
            ));
        }

        if ($section === 'enquiry-analysis') {
            return $this->enquiryAnalysis();
        }

        if ($section === 'call-enquiries' || $section === 'whatsapp-enquiries') {
            $type = ($section === 'call-enquiries') ? 'call' : 'whatsapp';

            if (Schema::hasTable('enquiry_interactions')) {
                $interactions = \App\Models\EnquiryInteraction::where('type', $type)->latest()->paginate(10);
            } else {
                $interactions = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            }

            return view('admin-dashboard', array_merge(
                $this->dashboardViewData($user),
                [
                    'currentPage' => $section,
                    'currentGroup' => 'enquiry-management',
                    'interactions' => $interactions,
                    'interactionType' => $type
                ]
            ));
        }

        if ($section === 'interior-enquiries') {
            $interiorEnquiriesTable = $this->interiorEnquiriesTable();
            $enquiries = $interiorEnquiriesTable
                ? DB::table($interiorEnquiriesTable)
                    ->where('deleted', 0)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10)
                : new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);

            return view('admin-dashboard', array_merge(
                $this->dashboardViewData($user),
                [
                    'currentPage' => 'interior-enquiries',
                    'currentGroup' => 'enquiry-management',
                    'interiorEnquiries' => $enquiries
                ]
            ));
        }

        if ($section === 'member-requests') {
            if (Schema::hasTable('member_requests')) {
                $requests = \DB::table('member_requests')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
            } else {    
                $requests = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            }

            return view('admin-dashboard', array_merge(
                $this->dashboardViewData($user),
                [
                    'currentPage' => 'member-requests',
                    'currentGroup' => 'seller-management',
                    'memberRequests' => $requests
                ]
            ));
        }

        if ($section === 'registered-vendors') {
            $vendors = \DB::table('vendors')
                ->leftJoin('vendor_infos', function ($join) {
                    $join->on('vendors.id', '=', 'vendor_infos.vendor_id')
                         ->where('vendor_infos.language_id', 20);
                })
                ->select('vendors.*', 'vendor_infos.name as display_name')
                ->paginate(12)
                ->through(function ($vendor) {
                    return $this->prepareVendorDetails($vendor);
                });

            return view('admin-dashboard', array_merge(
                $this->dashboardViewData($user),
                [
                    'currentPage' => 'registered-vendors',
                    'currentGroup' => 'seller-management',
                    'vendors' => $vendors
                ]
            ));
        }

        if ($section === 'media-library') {
            return view('admin-dashboard', array_merge(
                $this->dashboardViewData($user),
                [
                    'currentPage'  => 'media-library',
                    'currentGroup' => 'media-library',
                    'currentItem'  => [
                        'label' => 'Media Library',
                        'slug'  => 'media-library',
                        'group' => 'media-library',
                    ],
                    'mediaFolders' => MediaPath::imageFolders(),
                ]
            ));
        }

        $menu = array_merge($this->menuGroups(), $this->profileMenuGroups());
        $currentItem = $this->findMenuItem($menu, $section);

        abort_if(!$currentItem, 404, "Menu section not found.");

        $data = [
            'currentPage' => $currentItem['slug'] ?? 'dashboard',
            'currentGroup' => $currentItem['group'] ?? null,
            'currentItem' => $currentItem,
        ];

        if ($section === 'amenities') {
            $data['amenities'] = \App\Models\Amenity::orderBy('serial_number', 'asc')->get();
        } elseif ($section === 'categories') {
            $data['categories'] = \App\Models\Category::orderBy('serial_number', 'asc')->get();
        } elseif ($section === 'countries') {
            $data['countries'] = \App\Models\Country::withCount(['states', 'cities', 'propertyPlaces'])->orderBy('name', 'asc')->get();
        } elseif ($section === 'states') {
            $data['states'] = \App\Models\State::with('country')->withCount(['cities', 'propertyPlaces'])->orderBy('name', 'asc')->get();
            $data['countries'] = \App\Models\Country::orderBy('name')->get();
        } elseif ($section === 'cities') {
            $data['cities'] = \App\Models\City::with(['country', 'state'])->withCount('propertyPlaces')->orderBy('name', 'asc')->get();
            $data['countries'] = \App\Models\Country::orderBy('name')->get();
            $data['states'] = \App\Models\State::with('country')->orderBy('name')->get();
        } elseif ($section === 'property-places') {
            $data['property_places'] = \App\Models\PropertyPlace::with(['city', 'state', 'country'])->orderBy('id', 'asc')->get();
            $data['countries'] = \App\Models\Country::orderBy('name')->get();
            $data['states'] = \App\Models\State::with('country')->orderBy('name')->get();
            $data['cities'] = \App\Models\City::with(['country', 'state'])->orderBy('name')->get();
        } elseif ($section === 'change-password') {
            // Just render the form, no data needed
        } elseif ($section === 'manage-properties') {
            $connection = $this->resolvePropertiesConnection();

            if (! $connection || ! Schema::connection($connection)->hasTable('properties')) {
                $data['manageProperties'] = collect();
            } else {
                $baseSelect = [
                    'properties.id',
                    'properties.property_name',
                    'properties.type',
                    'properties.category_id',
                    'properties.property_area',
                    'properties.full_address',
                    'properties.latitude',
                    'properties.longitude',
                    'properties.status',
                    'properties.approve_status',
                    'properties.new_launched',
                    'properties.elite_project',
                    'properties.created_at',
                    'properties.vendor_id',
                    'properties.agent_id',
                ];

                if (Schema::connection($connection)->hasColumn('properties', 'display_image')) {
                    $baseSelect[] = 'properties.display_image';
                }
                if (Schema::connection($connection)->hasColumn('properties', 'main_property_image')) {
                    $baseSelect[] = 'properties.main_property_image';
                }
                if (Schema::connection($connection)->hasColumn('properties', 'floor_plan_image')) {
                    $baseSelect[] = 'properties.floor_plan_image';
                }
                if (Schema::connection($connection)->hasColumn('properties', 'brochure')) {
                    $baseSelect[] = 'properties.brochure';
                }

                $query = DB::connection($connection)->table('properties')
                    ->select($baseSelect)
                    ->orderByDesc('properties.id');

                if (Schema::connection($connection)->hasTable('property_slider_images')) {
                    $query->addSelect(DB::raw('(SELECT COUNT(*) FROM property_slider_images psi WHERE psi.property_id = properties.id) as gallery_images_count'));
                }

                if (Schema::connection($connection)->hasTable('property_floor_plan')) {
                    $query->addSelect(DB::raw('(SELECT COUNT(*) FROM property_floor_plan pfp WHERE pfp.property_id = properties.id) as floor_plan_images_count'));
                }

                if (Schema::connection($connection)->hasTable('property_categories')) {
                    $query->leftJoin('property_categories', 'property_categories.id', '=', 'properties.category_id')
                        ->addSelect('property_categories.name as category_name');
                }

                if (Schema::connection($connection)->hasTable('property_contents')) {
                    $latestContentSubquery = DB::connection($connection)
                        ->table('property_contents as pc')
                        ->select('pc.property_id', 'pc.title')
                        ->whereRaw('pc.id = (SELECT MAX(pc2.id) FROM property_contents pc2 WHERE pc2.property_id = pc.property_id)');

                    $query->leftJoinSub($latestContentSubquery, 'pc_latest', function ($join) {
                        $join->on('pc_latest.property_id', '=', 'properties.id');
                    })->addSelect('pc_latest.title as content_title');
                }

                if (Schema::connection($connection)->hasTable('vendors')) {
                    $query->leftJoin('vendors', 'vendors.id', '=', 'properties.vendor_id')
                        ->addSelect('vendors.first_name as vendor_first_name', 'vendors.last_name as vendor_last_name');
                }

                if (Schema::connection($connection)->hasTable('agents')) {
                    $query->leftJoin('agents', 'agents.id', '=', 'properties.agent_id')
                        ->addSelect('agents.username as agent_username');
                }

                $data['manageProperties'] = $query->get()->map(function ($property) {
                    $title = $property->content_title ?? $property->property_name;
                    $vendorName = trim(($property->vendor_first_name ?? '') . ' ' . ($property->vendor_last_name ?? ''));
                    $postBy = $vendorName !== '' ? $vendorName : ($property->agent_username ?? 'Admin');

                    $property->title = $title ?: 'Untitled Property';
                    $property->post_by = $postBy;
                    $property->type = $property->category_name ?? $property->type;
                    $property->cover_image = $property->display_image ?? ($property->main_property_image ?? null);
                    $property->gallery_images_count = (int) ($property->gallery_images_count ?? 0);
                    $property->floor_plan_images_count = (int) ($property->floor_plan_images_count ?? 0);

                    return $property;
                });
            }

        } elseif ($section === 'banner') {
            $data = array_merge($data, $this->bannerCollections());
        } elseif ($section === 'our-partners') {
            $data['partners'] = Schema::hasTable('our_partners')
                ? \App\Models\OurPartner::orderByDesc('id')->get()
                : collect();
        } elseif ($section === 'interior') {
            $data['interiorDesigns'] = Schema::hasTable('interior_designs')
                ? \App\Models\InteriorDesign::orderByDesc('id')->get()
                : collect();
        } elseif ($section === 'add-vendor') {
            // Render add vendor form
        } elseif ($section === 'registered-users') {
            $data['registeredUsers'] = User::orderBy('id', 'desc')->get();
        } elseif ($section === 'property-enquiries') {
            $connection = $this->resolvePropertiesConnection();
            $query = \App\Models\PropertyEnquiry::orderBy('id', 'desc');
            
            if ($connection && Schema::connection($connection)->hasTable('properties')) {
                // We can't easily join across different database connections in Eloquent/Query Builder in a single query
                // unless they are on the same server and we use DB::raw with database name.
                // For simplicity and robustness across environments, we'll fetch property names separately or use a collection map.
                $enquiries = $query->get();
                $propertyIds = $enquiries->pluck('property_id')->filter()->unique();
                $properties = DB::connection($connection)->table('properties')
                    ->whereIn('id', $propertyIds)
                    ->pluck('property_name', 'id');
                
                $enquiries->each(function($enquiry) use ($properties) {
                    $enquiry->property_name = $properties[$enquiry->property_id] ?? 'Unknown Property';
                });
                $data['enquiries'] = $enquiries;
            } else {
                $data['enquiries'] = $query->get()->each(function($enquiry) {
                    $enquiry->property_name = 'N/A';
                });
            }
        } elseif ($section === 'website-settings') {
            if (Schema::hasTable('website_settings')) {
                $data['settings'] = \App\Models\WebsiteSetting::getInstance();
            } else {
                $data['settings'] = new \App\Models\WebsiteSetting();
            }
        } elseif (str_ends_with($section, '-pinning')) {
            $connection = $this->resolvePropertiesConnection();
            $pinningProperties = collect();

            if ($connection && Schema::connection($connection)->hasTable('properties')) {
                $query = DB::connection($connection)->table('properties')
                    ->select('properties.id', 'properties.property_name', 'properties.property_area', 'properties.city');

                // Join vendors table for builder name
                if (Schema::connection($connection)->hasTable('vendors')) {
                    $query->leftJoin('vendors', 'vendors.id', '=', 'properties.vendor_id')
                        ->addSelect('vendors.first_name as vendor_first_name', 'vendors.last_name as vendor_last_name');
                }
                if (Schema::connection($connection)->hasTable('agents')) {
                    $query->leftJoin('agents', 'agents.id', '=', 'properties.agent_id')
                        ->addSelect('agents.username as agent_username');
                }

                // Apply automatic filtering based on the pinning page slug
                if ($section === 'chennai-page-pinning') {
                    $query->where('properties.city', 'LIKE', '%Chennai%');
                } elseif (in_array($section, ['apartment-pinning', 'plot-pinning', 'villa-pinning'])) {
                    $catNameMap = [
                        'apartment-pinning' => 'Apartment',
                        'plot-pinning' => 'Plot',
                        'villa-pinning' => 'Villa'
                    ];
                    $catName = $catNameMap[$section];
                    if (Schema::connection($connection)->hasTable('property_categories')) {
                        $cat = DB::connection($connection)->table('property_categories')->where('name', $catName)->first();
                        if ($cat) {
                            $query->where('properties.category_id', $cat->id);
                        }
                    }
                } elseif (in_array($section, ['best-deals-pinning', 'nri-investment-pinning', 'luxury-homes-pinning', 'best-location-picks-pinning'])) {
                    $pickMap = [
                        'best-deals-pinning' => 1,
                        'nri-investment-pinning' => 2,
                        'luxury-homes-pinning' => 3,
                        'best-location-picks-pinning' => 4
                    ];
                    $pickId = $pickMap[$section];
                    if (Schema::connection($connection)->hasTable('property_top_picks')) {
                        $query->join('property_top_picks', 'property_top_picks.property_id', '=', 'properties.id')
                            ->where('property_top_picks.top_picks_id', $pickId);
                    }
                }

                $pinningProperties = $query->orderBy('properties.property_name')->get()->map(function ($p) {
                    $vendorName = trim(($p->vendor_first_name ?? '') . ' ' . ($p->vendor_last_name ?? ''));
                    $p->builder = $vendorName !== '' ? $vendorName : ($p->agent_username ?? 'Admin');
                    $p->location = $p->property_area ?? $p->city ?? 'N/A';
                    return $p;
                });
            }

            // Get all pinned projects for this page, ordered by display_order
            $pinnedProjects = collect();
            if (Schema::hasTable('page_pinned_projects')) {
                $pinnedProjects = \App\Models\PagePinnedProject::where('page_slug', $section)
                    ->orderBy('display_order')
                    ->get();
            }

            // Build unique locations and builders for the filter dropdowns
            $pinningLocations = $pinningProperties->pluck('location')->filter()->unique()->sort()->values();
            $pinningBuilders = $pinningProperties->pluck('builder')->filter()->unique()->sort()->values();

            $data['pinningProperties'] = $pinningProperties;
            $data['pinnedProjects'] = $pinnedProjects;
            $data['pinningLocations'] = $pinningLocations;
            $data['pinningBuilders'] = $pinningBuilders;
            $data['pinningPageSlug'] = $section;
        }

        return view('admin-dashboard', array_merge(
            $this->dashboardViewData($user),
            $data
        ));
    }

    public function showVendor(int $vendor): View
    {
        $user = Auth::guard('admin')->user();
        $vendorRecord = $this->vendorDetailsQuery()
            ->where('vendors.id', $vendor)
            ->first();

        abort_if(! $vendorRecord, 404);

        $vendorRecord = $this->prepareVendorDetails($vendorRecord);
        $vendorProperties = $this->vendorProperties($vendor);

        return view('admin-dashboard', array_merge(
            $this->dashboardViewData($user),
            [
                'currentPage' => 'vendor-detail',
                'currentGroup' => 'seller-management',
                'currentItem' => [
                    'label' => 'Vendor Details',
                    'slug' => 'registered-vendors',
                    'group' => 'seller-management',
                ],
                'vendor' => $vendorRecord,
                'vendorProperties' => $vendorProperties,
            ]
        ));
    }

    public function secretLoginVendor(Request $request, int $vendor): RedirectResponse
    {
        abort_unless(Schema::hasTable('vendors') && DB::table('vendors')->where('id', $vendor)->exists(), 404);

        Auth::guard('vendor')->logout();
        $loggedInVendor = Auth::guard('vendor')->loginUsingId($vendor);

        abort_if(! $loggedInVendor, 404);

        $request->session()->regenerate();

        return redirect()
            ->route('vendor.dashboard')
            ->with('status', 'Secret login successful.');
    }

    public function editVendor(int $vendor): View
    {
        $user = Auth::guard('admin')->user();
        $vendorRecord = $this->vendorDetailsQuery()
            ->where('vendors.id', $vendor)
            ->first();

        abort_if(! $vendorRecord, 404);

        return view('admin-dashboard', array_merge(
            $this->dashboardViewData($user),
            [
                'currentPage' => 'vendor-edit',
                'currentGroup' => 'seller-management',
                'currentItem' => [
                    'label' => 'Edit Vendor',
                    'slug' => 'registered-vendors',
                    'group' => 'seller-management',
                ],
                'vendor' => $this->prepareVendorDetails($vendorRecord),
            ]
        ));
    }

    public function updateVendor(Request $request, int $vendor): RedirectResponse
    {
        $vendorRecord = DB::table('vendors')->where('id', $vendor)->first();
        abort_if(! $vendorRecord, 404);

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:vendors,email,' . $vendor,
            'phone' => 'nullable|string|max:255',
            'details' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'status' => 'nullable|in:0,1',
        ];

        if (Schema::hasColumn('vendors', 'username')) {
            $rules['username'] = 'nullable|string|max:255|unique:vendors,username,' . $vendor;
        }

        $validated = $request->validate($rules);

        $payload = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'details' => $validated['details'] ?? null,
            'status' => $validated['status'] ?? 1,
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('vendors', 'username')) {
            $payload['username'] = $validated['username'] ?? null;
        }

        if ($request->hasFile('photo')) {
            $payload['photo'] = $request->file('photo')->store('vendor_photos', 'public');
        }

        DB::table('vendors')->where('id', $vendor)->update($payload);

        if ($request->hasFile('photo')) {
            $this->deleteUploadedFile($vendorRecord->photo ?? null);
        }

        $this->syncVendorInfo($vendor, $validated);

        return redirect()
            ->route('admin.vendors.show', ['vendor' => $vendor])
            ->with('status', 'Vendor updated successfully.');
    }

    public function editVendorPassword(int $vendor): View
    {
        $user = Auth::guard('admin')->user();
        $vendorRecord = $this->vendorDetailsQuery()
            ->where('vendors.id', $vendor)
            ->first();

        abort_if(! $vendorRecord, 404);

        return view('admin-dashboard', array_merge(
            $this->dashboardViewData($user),
            [
                'currentPage' => 'vendor-password',
                'currentGroup' => 'seller-management',
                'currentItem' => [
                    'label' => 'Vendor Password',
                    'slug' => 'registered-vendors',
                    'group' => 'seller-management',
                ],
                'vendor' => $this->prepareVendorDetails($vendorRecord),
            ]
        ));
    }

    public function updateVendorPassword(Request $request, int $vendor): RedirectResponse
    {
        abort_unless(Schema::hasTable('vendors') && DB::table('vendors')->where('id', $vendor)->exists(), 404);

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::table('vendors')->where('id', $vendor)->update([
            'password' => Hash::make($validated['password']),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.vendors.show', ['vendor' => $vendor])
            ->with('status', 'Vendor password updated successfully.');
    }

    public function destroyVendor(int $vendor): RedirectResponse
    {
        $vendorRecord = DB::table('vendors')->where('id', $vendor)->first();
        abort_if(! $vendorRecord, 404);

        DB::transaction(function () use ($vendor) {
            if (Schema::hasTable('vendor_infos')) {
                DB::table('vendor_infos')->where('vendor_id', $vendor)->delete();
            }

            DB::table('vendors')->where('id', $vendor)->delete();
        });

        $this->deleteUploadedFile($vendorRecord->photo ?? null);

        return redirect()
            ->route('admin.section', ['section' => 'registered-vendors'])
            ->with('status', 'Vendor deleted successfully.');
    }

    public function savePinnedProject(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'page_slug' => 'required|string|max:255',
            'property_id' => 'required|integer',
            'display_order' => 'required|integer|min:1',
        ]);

        $connection = $this->resolvePropertiesConnection();
        $propertyName = null;
        $propertyLocation = null;
        $propertyBuilder = null;

        if ($connection && Schema::connection($connection)->hasTable('properties')) {
            $property = DB::connection($connection)->table('properties')
                ->where('id', $validated['property_id'])
                ->first();

            if ($property) {
                $propertyName = $property->property_name;
                $propertyLocation = $property->property_area ?? $property->city ?? null;

                // Get builder name
                $vendorName = '';
                if (Schema::connection($connection)->hasTable('vendors') && $property->vendor_id) {
                    $vendor = DB::connection($connection)->table('vendors')->where('id', $property->vendor_id)->first();
                    if ($vendor) {
                        $vendorName = trim(($vendor->first_name ?? '') . ' ' . ($vendor->last_name ?? ''));
                    }
                }
                if (empty($vendorName) && Schema::connection($connection)->hasTable('agents') && ($property->agent_id ?? null)) {
                    $agent = DB::connection($connection)->table('agents')->where('id', $property->agent_id)->first();
                    $vendorName = $agent->username ?? '';
                }
                $propertyBuilder = $vendorName ?: 'Admin';
            }
        }

        \App\Models\PagePinnedProject::updateOrCreate(
            [
                'page_slug' => $validated['page_slug'],
                'display_order' => $validated['display_order'],
            ],
            [
                'property_id' => $validated['property_id'],
                'property_name' => $propertyName,
                'property_location' => $propertyLocation,
                'property_builder' => $propertyBuilder,
            ]
        );

        $ordinal = $this->ordinalSuffix($validated['display_order']);
        return redirect()
            ->route('admin.section', ['section' => $validated['page_slug']])
            ->with('status', "Project pinned as {$ordinal} position successfully!");
    }

    public function removePinnedProject(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'page_slug' => 'required|string|max:255',
            'display_order' => 'required|integer|min:1',
        ]);

        \App\Models\PagePinnedProject::where('page_slug', $validated['page_slug'])
            ->where('display_order', $validated['display_order'])
            ->delete();

        return redirect()
            ->route('admin.section', ['section' => $validated['page_slug']])
            ->with('status', 'Pinned project removed successfully!');
    }

    private function ordinalSuffix(int $n): string
    {
        $s = ['th', 'st', 'nd', 'rd'];
        $v = $n % 100;
        return $n . ($s[($v - 20) % 10] ?? $s[$v] ?? $s[0]);
    }

    public function storeBanner(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:home_side,chennai_side,slider,list_view',
            'image' => 'nullable|image|max:2048',
            'selected_image_path' => 'nullable|string|max:255',
            'link' => 'nullable|url',
            'status' => 'nullable|in:1,0',
        ]);

        if (! Schema::hasTable('banners')) {
            return redirect()
                ->route('admin.section', ['section' => 'banner'])
                ->with('status', 'Banner table not found. Run the latest migrations first.');
        }

        if (! Schema::hasColumn('banners', 'image') && ! Schema::hasColumn('banners', 'file_path')) {
            return redirect()
                ->route('admin.section', ['section' => 'banner'])
                ->with('status', 'Banner table is missing an image/file_path column.');
        }

        $imageFile = $request->file('image');
        $selectedPath = $this->resolveBannerSelectedPath($validated['selected_image_path'] ?? null, $validated['type']);

        if (! $imageFile && ! $selectedPath) {
            return redirect()
                ->route('admin.section', ['section' => 'banner'])
                ->withErrors(['image' => 'Please upload an image or select one from the banner list.'])
                ->withInput();
        }

        $path = $imageFile ? $imageFile->store('banners', 'public') : $selectedPath;

        $payload = [
            'link' => $validated['link'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('banners', 'image')) {
            $payload['image'] = $path;
        }

        if (Schema::hasColumn('banners', 'file_path')) {
            $payload['file_path'] = $path;
        }

        if (Schema::hasColumn('banners', 'original_name')) {
            $payload['original_name'] = $imageFile ? $imageFile->getClientOriginalName() : basename($path);
        }

        if (Schema::hasColumn('banners', 'unique_name')) {
            $payload['unique_name'] = basename($path);
        }

        if (Schema::hasColumn('banners', 'type')) {
            $payload['type'] = $validated['type'];
        }

        if (Schema::hasColumn('banners', 'status')) {
            $payload['status'] = (int) ($validated['status'] ?? 1);
        }

        DB::table('banners')->insert($payload);

        return redirect()
            ->route('admin.section', ['section' => 'banner'])
            ->with('status', ucfirst(str_replace('_', ' ', $validated['type'])) . ' banner added successfully!');
    }

    public function destroyBanner(int $id): RedirectResponse
    {
        if (! Schema::hasTable('banners')) {
            return redirect()
                ->route('admin.section', ['section' => 'banner'])
                ->with('status', 'Banner table not found.');
        }

        $banner = DB::table('banners')->where('id', $id)->first();
        if ($banner) {
            $this->deleteUploadedFile($banner->image ?? $banner->file_path ?? null);
            DB::table('banners')->where('id', $id)->delete();
            return redirect()
                ->route('admin.section', ['section' => 'banner'])
                ->with('status', 'Banner deleted successfully!');
        }
        return redirect()
            ->route('admin.section', ['section' => 'banner'])
            ->with('status', 'Banner not found.');
    }

    public function updateBannerStatus(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate(['status' => 'required|in:1,0']);

        if (! Schema::hasTable('banners')) {
            return redirect()
                ->route('admin.section', ['section' => 'banner'])
                ->with('status', 'Banner table not found.');
        }

        if (! Schema::hasColumn('banners', 'status')) {
            return redirect()
                ->route('admin.section', ['section' => 'banner'])
                ->with('status', 'Banner status column not found. Run the latest banner migration first.');
        }

        DB::table('banners')->where('id', $id)->update([
            'status' => $validated['status'],
            'updated_at' => now(),
        ]);
        return redirect()
            ->route('admin.section', ['section' => 'banner'])
            ->with('status', 'Banner status updated successfully!');
    }

    private function bannerCollections(): array
    {
        $empty = collect();

        if (! Schema::hasTable('banners')) {
            return [
                'sliderBanners' => $empty,
                'listViewBanners' => $empty,
            ];
        }

        $banners = DB::table('banners')->orderBy('id', 'desc')->get()->map(function ($banner) {
            $banner->image = $banner->image ?? $banner->file_path ?? null;
            $banner->status = isset($banner->status) ? (int) $banner->status : 1;
            $banner->type = $banner->type ?? 'slider';

            return $banner;
        });

        if (! Schema::hasColumn('banners', 'type')) {
            return [
                'sliderBanners' => $banners->values(),
                'listViewBanners' => $empty,
            ];
        }

        return [
            'sliderBanners' => $banners->whereIn('type', ['home_side', 'slider', 'home'])->values(),
            'listViewBanners' => $banners->whereIn('type', ['chennai_side', 'list_view', 'chennai'])->values(),
            'bannerHasStatus' => Schema::hasColumn('banners', 'status'),
            'listBannerImages' => $this->bannerFolderImages('list_banners'),
        ];
    }

    private function bannerFolderImages(string $folder): array
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'svg'];
        $roots = [
            [
                'path' => public_path('storage/public/' . $folder),
                'prefix' => 'storage/public/' . $folder,
            ],
            [
                'path' => storage_path('app/public/newimg/' . $folder),
                'prefix' => 'newimg/' . $folder,
            ],
            [
                'path' => public_path('images/properties_img/' . $folder),
                'prefix' => 'images/properties_img/' . $folder,
            ],
        ];

        $images = [];
        $seen = [];

        foreach ($roots as $root) {
            if (! is_dir($root['path'])) {
                continue;
            }

            foreach (glob($root['path'] . DIRECTORY_SEPARATOR . '*') ?: [] as $file) {
                if (! is_file($file)) {
                    continue;
                }

                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (! in_array($extension, $allowedExtensions, true)) {
                    continue;
                }

                $filename = basename($file);
                if (isset($seen[$filename])) {
                    continue;
                }
                $seen[$filename] = true;

                $path = $root['prefix'] . '/' . $filename;
                $images[] = [
                    'filename' => $filename,
                    'path' => $path,
                    'url' => MediaPath::url($path),
                ];
            }
        }

        usort($images, fn (array $left, array $right) => strcmp($left['filename'], $right['filename']));

        return $images;
    }

    private function resolveBannerSelectedPath(?string $path, string $type): ?string
    {
        if (! is_string($path) || trim($path) === '') {
            return null;
        }

        $path = ltrim(str_replace('\\', '/', trim($path)), '/');
        $allowedFolder = in_array($type, ['chennai_side', 'list_view', 'chennai'], true) ? 'list_banners' : 'home_banners';

        foreach ($this->bannerFolderImages($allowedFolder) as $image) {
            if (hash_equals($image['path'], $path)) {
                return $path;
            }
        }

        return null;
    }

    public function storeOurPartner(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'projects' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|string',
        ]);

        $imagePath = $request->file('image')?->store('partners', 'public');

        \App\Models\OurPartner::create([
            'name' => $validated['name'],
            'projects' => $validated['projects'] ?? null,
            'image' => $imagePath,
            'status' => $validated['status'] === 'Active' || $validated['status'] == 1 ? 1 : 0,
        ]);

        return redirect()
            ->route('admin.section', ['section' => 'our-partners'])
            ->with('status', 'Partner added successfully!');
    }

    public function updateOurPartner(Request $request, \App\Models\OurPartner $partner): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'projects' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|string',
        ]);

        $imagePath = $partner->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('partners', 'public');
            $this->deleteUploadedFile($partner->image);
        }

        $partner->update([
            'name' => $validated['name'],
            'projects' => $validated['projects'] ?? null,
            'image' => $imagePath,
            'status' => $validated['status'] === 'Active' || $validated['status'] == 1 ? 1 : 0,
        ]);

        return redirect()
            ->route('admin.section', ['section' => 'our-partners'])
            ->with('status', 'Partner updated successfully!');
    }

    public function destroyOurPartner(\App\Models\OurPartner $partner): RedirectResponse
    {
        $this->deleteUploadedFile($partner->image);
        $partner->delete();

        return redirect()
            ->route('admin.section', ['section' => 'our-partners'])
            ->with('status', 'Partner deleted successfully!');
    }

    public function storeInteriorDesign(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|string',
        ]);

        $imagePath = $request->file('image')?->store('interior-designs', 'public');

        $statusInput = $validated['status'];
        $statusVal = ($statusInput === '1' || $statusInput === 'Active' || $statusInput == 1) ? 'active' : 'inactive';

        $data = [
            'name' => $validated['name'],
            'status' => $statusVal,
        ];

        $this->setInteriorImageData($data, $imagePath);

        \App\Models\InteriorDesign::create($data);

        return redirect()
            ->route('admin.section', ['section' => 'interior'])
            ->with('status', 'Interior design added successfully!');
    }

    public function updateInteriorDesign(Request $request, \App\Models\InteriorDesign $interior): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|string',
        ]);

        $existingImage = $interior->image;
        $imagePath = $existingImage;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('interior-designs', 'public');
            if ($existingImage) {
                $this->deleteUploadedFile($existingImage);
            }
        }

        $statusInput = $validated['status'];
        $statusVal = ($statusInput === '1' || $statusInput === 'Active' || $statusInput == 1) ? 'active' : 'inactive';

        $data = [
            'name' => $validated['name'],
            'status' => $statusVal,
        ];

        $this->setInteriorImageData($data, $imagePath);

        $interior->update($data);

        return redirect()
            ->route('admin.section', ['section' => 'interior'])
            ->with('status', 'Interior design updated successfully!');
    }

    public function destroyInteriorDesign(\App\Models\InteriorDesign $interior): RedirectResponse
    {
        if ($interior->image) {
            $this->deleteUploadedFile($interior->image);
        }
        $interior->delete();

        return redirect()
            ->route('admin.section', ['section' => 'interior'])
            ->with('status', 'Interior design deleted successfully!');
    }

    private function setInteriorImageData(array &$data, ?string $imagePath): void
    {
        if (Schema::hasColumn('interior_designs', 'images')) {
            $data['images'] = $imagePath ? json_encode([$imagePath]) : null;
        }

        if (Schema::hasColumn('interior_designs', 'image')) {
            $data['image'] = $imagePath;
        }
    }

    public function storeVendor(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:vendors,email',
            'phone' => 'nullable|string|max:255',
            'details' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = $request->file('photo')?->store('vendor_photos', 'public');

        $vendorId = DB::table('vendors')->insertGetId([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'details' => $validated['details'] ?? null,
            'photo' => $photoPath,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'username' => null,
            'password' => null,
            'status' => 1,
            'amount' => 0,
            'vendor_request' => 'approved',
            'avg_rating' => 0,
            'show_email_addresss' => 1,
            'show_phone_number' => 1,
            'show_contact_form' => 1,
            'is_admin' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('vendor_infos')->insert([
            'vendor_id' => $vendorId,
            'language_id' => 20,
            'name' => trim($validated['first_name'] . ' ' . $validated['last_name']),
            'details' => $validated['details'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.section', ['section' => 'registered-vendors'])
            ->with('status', 'Vendor added successfully!');
    }

    public function storeAmenity(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string',
            'color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'status' => 'required|string',
            'serial_number' => 'required|integer',
        ]);

        \App\Models\Amenity::create([
            'name' => $validated['name'],
            'icon' => $validated['icon'],
            'color' => $request->filled('color') ? $validated['color'] : '#000000',
            'status' => strtolower($validated['status']) === 'active' ? 'active' : 'inactive',
            'serial_number' => $validated['serial_number'],
        ]);

        return redirect()
            ->route('admin.section', [ 'section' => 'amenities'])
            ->with('status', 'Amenity added successfully!');
    }

    public function updateAmenity(Request $request, \App\Models\Amenity $amenity): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string',
            'color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'status' => 'required|string',
            'serial_number' => 'required|integer',
        ]);

        $amenity->update([
            'name' => $validated['name'],
            'icon' => $validated['icon'],
            'color' => $request->filled('color') ? $validated['color'] : $amenity->color,
            'status' => strtolower($validated['status']) === 'active' ? 'active' : 'inactive',
            'serial_number' => $validated['serial_number'],
        ]);
        return redirect()
            ->route('admin.section', [ 'section' => 'amenities'])
            ->with('status', 'Amenity updated successfully!');
    }

    public function destroyAmenity(\App\Models\Amenity $amenity): RedirectResponse
    {
        $amenity->delete();

        return redirect()
            ->route('admin.section', [ 'section' => 'amenities'])
            ->with('status', 'Amenity deleted successfully!');
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'status' => 'required|string',
            'serial_number' => 'required|integer',
        ]);

        \App\Models\Category::create([
            'type' => $validated['type'],
            'name' => $validated['name'],
            'status' => strtolower($validated['status']) === 'active' ? 'active' : 'inactive',
            'serial_number' => $validated['serial_number'],
        ]);

        return redirect()
            ->route('admin.section', [ 'section' => 'categories'])
            ->with('status', 'Category added successfully!');
    }

    public function updateCategory(Request $request, \App\Models\Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'status' => 'required|string',
            'serial_number' => 'required|integer',
        ]);

        $category->update([
            'type' => $validated['type'],
            'name' => $validated['name'],
            'status' => strtolower($validated['status']) === 'active' ? 'active' : 'inactive',
            'serial_number' => $validated['serial_number'],
        ]);

        return redirect()
            ->route('admin.section', [ 'section' => 'categories'])
            ->with('status', 'Category updated successfully!');
    }

    public function destroyCategory(\App\Models\Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()
            ->route('admin.section', [ 'section' => 'categories'])
            ->with('status', 'Category deleted successfully!');
    }

    public function storeCountry(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        \App\Models\Country::create([
            'name' => $validated['name'],
        ]);

        return redirect()
            ->route('admin.section', [ 'section' => 'countries'])
            ->with('status', 'Country added successfully!');
    }

    public function updateCountry(Request $request, \App\Models\Country $country): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $country->update([
            'name' => $validated['name'],
        ]);

        return redirect()
            ->route('admin.section', [ 'section' => 'countries'])
            ->with('status', 'Country updated successfully!');
    }

    public function destroyCountry(\App\Models\Country $country): RedirectResponse
    {
        $country->delete();

        return redirect()
            ->route('admin.section', [ 'section' => 'countries'])
            ->with('status', 'Country deleted successfully!');
    }

    public function storeState(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:property_countries,id',
        ]);

        \App\Models\State::create([
            'name' => $validated['name'],
            'country_id' => $validated['country_id'],
        ]);

        return redirect()
            ->route('admin.section', [ 'section' => 'states'])
            ->with('status', 'State added successfully!');
    }

    public function updateState(Request $request, \App\Models\State $state): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:property_countries,id',
        ]);

        $state->update([
            'name' => $validated['name'],
            'country_id' => $validated['country_id'],
        ]);

        return redirect()
            ->route('admin.section', [ 'section' => 'states'])
            ->with('status', 'State updated successfully!');
    }

    public function destroyState(\App\Models\State $state): RedirectResponse
    {
        $state->delete();

        return redirect()
            ->route('admin.section', [ 'section' => 'states'])
            ->with('status', 'State deleted successfully!');
    }

    public function storeCity(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:property_countries,id',
            'state_id' => 'required|exists:property_states,id',
        ]);

        $data = [
            'name' => $validated['name'],
            'country_id' => $validated['country_id'],
            'state_id' => $validated['state_id'],
        ];

        if (Schema::hasColumn('property_cities', 'image')) {
            $data['image'] = '';
        }

        if (Schema::hasColumn('property_cities', 'status')) {
            $data['status'] = 1;
        }

        \App\Models\City::create($data);

        return redirect()
            ->route('admin.section', [ 'section' => 'cities'])
            ->with('status', 'City added successfully!');
    }

    public function updateCity(Request $request, \App\Models\City $city): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:property_countries,id',
            'state_id' => 'required|exists:property_states,id',
        ]);

        $city->update([
            'name' => $validated['name'],
            'country_id' => $validated['country_id'],
            'state_id' => $validated['state_id'],
        ]);

        return redirect()
            ->route('admin.section', [ 'section' => 'cities'])
            ->with('status', 'City updated successfully!');
    }

    public function destroyCity(\App\Models\City $city): RedirectResponse
    {
        $city->delete();

        return redirect()
            ->route('admin.section', [ 'section' => 'cities'])
            ->with('status', 'City deleted successfully!');
    }

    public function storePropertyPlace(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:property_cities,id',
            'state_id' => 'required|exists:property_states,id',
            'country_id' => 'required|exists:property_countries,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $validated['name'],
            'city_id' => $validated['city_id'],
            'state_id' => $validated['state_id'],
            'country_id' => $validated['country_id'],
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('property_places', 'public');
        }

        \App\Models\PropertyPlace::create($data);

        return redirect()
            ->route('admin.section', [ 'section' => 'property-places'])
            ->with('status', 'Property Place added successfully!');
    }

    public function editPropertyPlace(\App\Models\PropertyPlace $property_place): View
    {
        $admin = Auth::guard('admin')->user();
        return view('admin-dashboard', array_merge(
            $this->dashboardViewData($admin),
            [
                'currentPage' => 'edit-property-place',
                'currentGroup' => 'property-specification',
                'currentItem' => [
                    'label' => 'Edit Property Place',
                    'slug' => 'property-places',
                    'group' => 'property-specification',
                ],
                'property_place' => $property_place,
                'countries' => \App\Models\Country::orderBy('name')->get(),
                'states' => \App\Models\State::with('country')->orderBy('name')->get(),
                'cities' => \App\Models\City::with(['country', 'state'])->orderBy('name')->get(),
            ]
        ));
    }

    public function updatePropertyPlace(Request $request, \App\Models\PropertyPlace $property_place): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:property_cities,id',
            'state_id' => 'required|exists:property_states,id',
            'country_id' => 'required|exists:property_countries,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $validated['name'],
            'city_id' => $validated['city_id'],
            'state_id' => $validated['state_id'],
            'country_id' => $validated['country_id'],
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('property_places', 'public');
        }

        $property_place->update($data);

        return redirect()
            ->route('admin.section', [ 'section' => 'property-places'])
            ->with('status', 'Property Place updated successfully!');
    }

    public function destroyPropertyPlace(\App\Models\PropertyPlace $property_place): RedirectResponse
    {
        $property_place->delete();

        return redirect()
            ->route('admin.section', [ 'section' => 'property-places'])
            ->with('status', 'Property Place deleted successfully!');
    }

    public function updateUser(Request $request, User $targetUser): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $targetUser->id,
            'phone' => 'required|string|max:20',
            'account_status' => 'nullable|in:active,inactive,1,0',
            'email_status' => 'nullable|in:verified,unverified',
        ]);

        $targetUser->first_name = $validated['first_name'];
        if (Schema::hasColumn('users', 'last_name')) {
            $targetUser->last_name = $validated['last_name'];
        }

        if (Schema::hasColumn('users', 'name')) {
            $targetUser->name = trim($validated['first_name'] . ' ' . $validated['last_name']);
        }

        $targetUser->email = $validated['email'];
        $targetUser->phone = $validated['phone'] ?? $targetUser->phone;

        if (isset($validated['account_status'])) {
            $targetUser->status = in_array($validated['account_status'], ['active', '1', 1], true) ? 1 : 0;
        }
        if (isset($validated['email_status'])) {
            $targetUser->email_verified_at = ($validated['email_status'] === 'verified') ? now() : null;
        }

        $targetUser->save();
        return redirect()
            ->route('admin.section', [ 'section' => 'registered-users'])
            ->with('status', 'User updated successfully!');
    }

    public function toggleUserStatus(User $targetUser): RedirectResponse
    {
        if (!Schema::hasColumn('users', 'status')) {
            return redirect()
                ->route('admin.section', [ 'section' => 'registered-users'])
                ->with('status', 'Account status field unavailable.');
        }

        $targetUser->status = ($targetUser->status == 'active' || $targetUser->status == 1) ? 0 : 1;
        $targetUser->save();

        return redirect()
            ->route('admin.section', [ 'section' => 'registered-users'])
            ->with('status', 'User status updated successfully!');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:admins,username,' . $admin->id],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email,' . $admin->id],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'about' => ['nullable', 'string', 'max:1000'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($request->hasFile('profile_image')) {
            if ($admin->profile_image) {
                Storage::disk('public')->delete($admin->profile_image);
            }
            $admin->profile_image = $request->file('profile_image')->store('profile_images', 'public');
        }

        $admin->username = $validated['username'];
        $admin->email = $validated['email'];
        $admin->first_name = $validated['first_name'];
        $admin->last_name = $validated['last_name'];
        $admin->name = trim($validated['first_name'] . ' ' . $validated['last_name']);
        $admin->contact_number = $validated['phone'];
        $admin->country = $validated['country'];
        $admin->city = $validated['city'];
        $admin->state = $validated['state'] ?? 'Tamilnadu';
        $admin->zip_code = $validated['zip_code'];
        $admin->address = $validated['address'];
        $admin->about = $validated['about'];

        $admin->save();

        return redirect()->back()->with('status', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Current password is required',
            'password.required' => 'New password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
        ]);

        if (!
\Hash::check($validated['current_password'], $admin->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $admin->password = \Hash::make($validated['password']);
        $admin->save();

        return redirect()->back()->with('status', 'Password updated successfully.');
    }

    public function updateWebsiteSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'website_title' => ['nullable', 'string', 'max:255'],
            'website_description' => ['nullable', 'string', 'max:1000'],
            'email_address' => ['nullable', 'email', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'enable_smtp' => ['nullable', 'boolean'],
            'smtp_host' => ['nullable', 'string', 'max:255'],
            'smtp_port' => ['nullable', 'string', 'max:10'],
            'smtp_encryption' => ['nullable', 'string', 'max:10'],
            'smtp_username' => ['nullable', 'string', 'max:255'],
            'smtp_password' => ['nullable', 'string'],
            'from_mail' => ['nullable', 'email', 'max:255'],
            'from_name' => ['nullable', 'string', 'max:255'],
            'website_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'website_favicon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,ico', 'max:1024'],
        ]);

        $settings = \App\Models\WebsiteSetting::getInstance();

        // Handle logo upload
        if ($request->hasFile('website_logo')) {
            if ($settings->website_logo) {
                Storage::disk('public')->delete($settings->website_logo);
            }
            $validated['website_logo'] = $request->file('website_logo')->store('website', 'public');
        }

        // Handle favicon upload
        if ($request->hasFile('website_favicon')) {
            if ($settings->website_favicon) {
                Storage::disk('public')->delete($settings->website_favicon);
            }
            $validated['website_favicon'] = $request->file('website_favicon')->store('website', 'public');
        }

        $validated['enable_smtp'] = $request->has('enable_smtp');

        if ($settings->id) {
            $settings->update($validated);
        } else {
            \App\Models\WebsiteSetting::create($validated);
        }

        return redirect()->back()->with('status', 'Website settings updated successfully.');
    }

    public function toggleEmailVerification(User $targetUser): RedirectResponse
    {
        if (!Schema::hasColumn('users', 'email_verified_at')) {
            return redirect()
                ->route('admin.section', [ 'section' => 'registered-users'])
                ->with('status', 'Email verification field unavailable.');
        }

        $targetUser->email_verified_at = $targetUser->email_verified_at ? null : now();
        $targetUser->save();

        return redirect()
            ->route('admin.section', [ 'section' => 'registered-users'])
            ->with('status', 'Email verification status updated successfully!');
    }

    public function updateEmailVerification(Request $request, User $targetUser): RedirectResponse
    {
        if (!Schema::hasColumn('users', 'email_verified_at')) {
            return redirect()
                ->route('admin.section', [ 'section' => 'registered-users'])
                ->with('status', 'Email verification field unavailable.');
        }

        $validated = $request->validate(['email_status' => 'required|in:verified,unverified']);
        $targetUser->email_verified_at = $validated['email_status'] === 'verified' ? now() : null;
        $targetUser->save();

        return redirect()
            ->route('admin.section', [ 'section' => 'registered-users'])
            ->with('status', 'Email verification status updated successfully!');
    }

    public function createUser(): View
    {
        $user = Auth::guard('admin')->user();

        return view('admin-dashboard', array_merge(
            $this->dashboardViewData($user),
            [
                'currentPage' => 'add-user',
                'currentGroup' => 'user-management',
                'currentItem' => [
                    'label' => 'Add User',
                    'slug' => 'add-user',
                    'group' => 'user-management',
                ],
                'mode' => 'create',
            ]
        ));
    }

    public function editUser(User $targetUser): View
    {
        $user = Auth::guard('admin')->user();

        return view('admin-dashboard', array_merge(
            $this->dashboardViewData($user),
            [
                'currentPage' => 'edit-user',
                'currentGroup' => 'user-management',
                'currentItem' => [
                    'label' => 'Edit User',
                    'slug' => 'edit-user',
                    'group' => 'user-management',
                ],
                'targetUser' => $targetUser,
                'mode' => 'edit',
            ]
        ));
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
        ]);

        $randomPassword = Str::random(16); // secure random password for admin-created users

        $payload = [
            'email' => $validated['email'],
            'password' => bcrypt($randomPassword),
        ];

        if (Schema::hasColumn('users', 'first_name')) {
            $payload['first_name'] = $validated['first_name'];
        }

        if (Schema::hasColumn('users', 'last_name')) {
            $payload['last_name'] = $validated['last_name'];
        }

        if (Schema::hasColumn('users', 'name')) {
            $payload['name'] = trim($validated['first_name'] . ' ' . $validated['last_name']);
        }

        if (Schema::hasColumn('users', 'phone')) {
            $payload['phone'] = $validated['phone'];
        }

        if (Schema::hasColumn('users', 'contact_number')) {
            $payload['contact_number'] = $validated['phone'];
        }

        if (Schema::hasColumn('users', 'status')) {
            $payload['status'] = 1;
        }

        User::create($payload);

        return redirect()
            ->route('admin.section', [ 'section' => 'registered-users'])
            ->with('status', 'User added successfully!');
    }

    public function updateUserStatus(Request $request, User $targetUser): RedirectResponse
    {
        if (!Schema::hasColumn('users', 'status')) {
            return redirect()
                ->route('admin.section', [ 'section' => 'registered-users'])
                ->with('status', 'Account status field unavailable.');
        }

        $validated = $request->validate(['account_status' => 'required|in:active,inactive,1,0']);
        $status = $validated['account_status'];
        $targetUser->status = in_array($status, ['active', 1, '1'], true) ? 1 : 0;
        $targetUser->save();

        return redirect()
            ->route('admin.section', [ 'section' => 'registered-users'])
            ->with('status', 'Account status updated successfully!');
    }

    public function updateEnquiryStatus(Request $request, \App\Models\PropertyEnquiry $enquiry): RedirectResponse
    {
        $validated = $request->validate(['account_status' => 'required|in:active,inactive,1,0,received,closed']);
        $status = $validated['account_status'];

        $enquiry->enquiry_status = in_array($status, ['active', 1, '1', 'received'], true) ? 'received' : 'closed';
        $enquiry->save();

        return redirect()->back()->with('status', 'Enquiry status updated successfully!');
    }

    public function updateInteriorEnquiryStatus(Request $request, int $id): RedirectResponse
    {
        $status = $request->input('account_status');
        if (!in_array($status, ['pending', 'contacted', 'closed'])) {
            return redirect()->back()->with('error', 'Invalid status.');
        }

        $interiorEnquiriesTable = $this->interiorEnquiriesTable();
        if (! $interiorEnquiriesTable) {
            return redirect()->back()->with('error', 'Interior enquiries table not found.');
        }

        DB::table($interiorEnquiriesTable)->where('id', $id)->update(['status' => $status]);

        return redirect()->back()->with('status', 'Interior enquiry status updated successfully!');
    }

    public function destroyUser(User $targetUser): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        if ($admin && $admin->id === $targetUser->id) {
            return redirect()
                ->route('admin.section', [ 'section' => 'registered-users'])
                ->with('status', 'You cannot delete your own account from here.');
        }

        $targetUser->delete();

        return redirect()
            ->route('admin.section', [ 'section' => 'registered-users'])
            ->with('status', 'User removed successfully!');
    }

    private function vendorDetailsQuery()
    {
        $query = DB::table('vendors')->select('vendors.*');

        if (Schema::hasTable('vendor_infos')) {
            $query->leftJoin('vendor_infos', function ($join) {
                $join->on('vendors.id', '=', 'vendor_infos.vendor_id')
                    ->where('vendor_infos.language_id', 20);
            });

            if (Schema::hasColumn('vendor_infos', 'name')) {
                $query->addSelect('vendor_infos.name as display_name');
            }

            if (Schema::hasColumn('vendor_infos', 'details')) {
                $query->addSelect('vendor_infos.details as info_details');
            }
        }

        return $query;
    }

    private function interiorEnquiriesTable(): ?string
    {
        foreach (['interiar_enquiries', 'interior_enquiries'] as $table) {
            if (Schema::hasTable($table)) {
                return $table;
            }
        }

        return null;
    }

    private function formatDashboardScore(int $total): string
    {
        $score = min(10, round($total / 10, 1));
        $scoreText = fmod($score, 1.0) === 0.0 ? number_format($score, 0) : number_format($score, 1);

        return $scoreText . '/10 Score';
    }

    private function prepareVendorDetails(object $vendor): object
    {
        $displayName = trim((string) ($vendor->display_name ?? ''));
        $fallbackName = trim(($vendor->first_name ?? '') . ' ' . ($vendor->last_name ?? ''));
        $vendor->display_name = $displayName !== '' ? $displayName : ($fallbackName !== '' ? $fallbackName : 'Unnamed Vendor');

        if (trim((string) ($vendor->info_details ?? '')) !== '') {
            $vendor->details = $vendor->info_details;
        }

        $vendor->photo = $this->normalizeVendorPhotoPath($vendor->photo ?? null);
        $vendor->logo = $vendor->photo ?? null;

        return $vendor;
    }

    private function normalizeVendorPhotoPath(?string $photo): ?string
    {
        if (! is_string($photo) || trim($photo) === '') {
            return null;
        }

        $photo = trim(str_replace('\\', '/', $photo));

        if (
            str_starts_with($photo, 'http://')
            || str_starts_with($photo, 'https://')
            || str_starts_with($photo, 'data:')
            || str_contains($photo, '/')
        ) {
            return ltrim($photo, '/');
        }

        $storagePublicCandidate = public_path('storage/public/vendor_photos/' . $photo);
        if (is_file($storagePublicCandidate)) {
            return 'vendor_photos/' . $photo;
        }

        $storageCandidate = storage_path('app/public/vendor_photos/' . $photo);
        if (is_file($storageCandidate)) {
            return 'vendor_photos/' . $photo;
        }

        $legacyPublicCandidate = public_path('assets/images/vendors/' . $photo);
        if (is_file($legacyPublicCandidate)) {
            return $photo;
        }

        return 'vendor_photos/' . $photo;
    }

    private function syncVendorInfo(int $vendorId, array $validated): void
    {
        if (! Schema::hasTable('vendor_infos')) {
            return;
        }

        $payload = ['updated_at' => now()];

        if (Schema::hasColumn('vendor_infos', 'name')) {
            $payload['name'] = trim($validated['first_name'] . ' ' . $validated['last_name']);
        }

        if (Schema::hasColumn('vendor_infos', 'details')) {
            $payload['details'] = $validated['details'] ?? null;
        }

        $exists = DB::table('vendor_infos')
            ->where('vendor_id', $vendorId)
            ->where('language_id', 20)
            ->exists();

        if ($exists) {
            DB::table('vendor_infos')
                ->where('vendor_id', $vendorId)
                ->where('language_id', 20)
                ->update($payload);

            return;
        }

        $payload['vendor_id'] = $vendorId;
        $payload['language_id'] = 20;
        $payload['created_at'] = now();

        DB::table('vendor_infos')->insert($payload);
    }

    private function vendorProperties(int $vendorId)
    {
        $connection = $this->resolvePropertiesConnection();

        if (
            ! $connection ||
            ! Schema::connection($connection)->hasTable('properties') ||
            ! Schema::connection($connection)->hasColumn('properties', 'vendor_id')
        ) {
            return collect();
        }

        $columns = ['id'];
        foreach (['property_name', 'type', 'property_area', 'city', 'status', 'approve_status', 'created_at'] as $column) {
            if (Schema::connection($connection)->hasColumn('properties', $column)) {
                $columns[] = $column;
            }
        }

        return DB::connection($connection)->table('properties')
            ->select($columns)
            ->where('vendor_id', $vendorId)
            ->orderByDesc('id')
            ->limit(25)
            ->get();
    }

    private function dashboardViewData($user): array
    {
        $connection = $this->resolvePropertiesConnection();
        $totalProperties = 0;
        if ($connection && Schema::connection($connection)->hasTable('properties')) {
            $totalProperties = DB::connection($connection)->table('properties')->count();
        }

        $totalVendors = Schema::hasTable('vendors') ? DB::table('vendors')->count() : 0;
        $interiorEnquiriesTable = $this->interiorEnquiriesTable();
        $totalInteriorEnquiries = $interiorEnquiriesTable ? DB::table($interiorEnquiriesTable)->where('deleted', 0)->count() : 0;

        return [
            'user' => $user,
            'stats' => [
                [
                    'label' => 'Total User',
                    'value' => number_format(User::count()),
                    'change' => $this->formatDashboardScore(User::count()),
                    'tone' => 'lavender',
                    'icon' => 'users',
                    'slug' => 'registered-users',
                ],
                [
                    'label' => 'Property Seller',
                    'value' => number_format($totalVendors),
                    'change' => $this->formatDashboardScore($totalVendors),
                    'tone' => 'gold',
                    'icon' => 'box',
                    'slug' => 'registered-vendors',
                ],
                [
                    'label' => 'Total Properties',
                    'value' => number_format($totalProperties),
                    'change' => $this->formatDashboardScore($totalProperties),
                    'tone' => 'mint',
                    'icon' => 'chart',
                    'slug' => 'manage-properties',
                ],
                [
                    'label' => 'Total Property Enquiries',
                    'value' => number_format(\App\Models\PropertyEnquiry::count()),
                    'change' => $this->formatDashboardScore(\App\Models\PropertyEnquiry::count()),
                    'tone' => 'peach',
                    'icon' => 'prop-enquiry',
                    'slug' => 'property-enquiry',
                ],
                [
                    'label' => 'Total Interior Enquiries',
                    'value' => number_format($totalInteriorEnquiries),
                    'change' => $this->formatDashboardScore($totalInteriorEnquiries),
                    'tone' => 'peach',
                    'icon' => 'interior',
                    'slug' => 'interior-enquiries',
                ],
            ],
            'menuGroups' => $this->menuGroups(),
            'profileGroups' => $this->profileMenuGroups(),
            'currentPage' => 'dashboard',
            'currentGroup' => null,
            'currentItem' => null,
        ];
    }

    private function menuGroups(): array
    {
        return [
            [
                'label' => 'Property-specification',
                'key' => 'property-specification',
                'items' => [
                    ['label' => 'Amenities', 'slug' => 'amenities'],
                    ['label' => 'Categories', 'slug' => 'categories'],
                    ['label' => 'Countries', 'slug' => 'countries'],
                    ['label' => 'States', 'slug' => 'states'],
                    ['label' => 'Cities', 'slug' => 'cities'],
                    ['label' => 'Property Places', 'slug' => 'property-places'],
                ],
            ],
            [
                'label' => 'User Management',
                'key' => 'user-management',
                'items' => [
                    ['label' => 'Registered Users', 'slug' => 'registered-users'],
                   
                ],
            ],
            [
                'label' => 'Master Management',
                'key' => 'master-management',
                'items' => [
                    ['label' => 'Chennai Page Pinning', 'slug' => 'chennai-page-pinning'],
                    ['label' => 'Best Deals Pinning', 'slug' => 'best-deals-pinning'],
                    ['label' => 'NRI Investment Pinning', 'slug' => 'nri-investment-pinning'],
                    ['label' => 'Luxury Homes Pinning', 'slug' => 'luxury-homes-pinning'],
                    ['label' => 'Best Location Picks Pinning', 'slug' => 'best-location-picks-pinning'],
                    ['label' => 'Apartment Pinning', 'slug' => 'apartment-pinning'],
                    ['label' => 'Villa Pinning', 'slug' => 'villa-pinning'],
                    ['label' => 'Plot Pinning', 'slug' => 'plot-pinning'],
                ],
            ],
            [
                'label' => 'Property Management',
                'key' => 'property-management',
                'items' => [
                    ['label' => 'Manage properties', 'slug' => 'manage-properties'],
                ],
            ],
            [
                'label' => 'Enquiry Management',
                'key' => 'enquiry-management',
                'items' => [
                    ['label' => 'Property Enquiries', 'slug' => 'property-enquiry'],
                    ['label' => 'Call Enquiries', 'slug' => 'call-enquiries'],
                    ['label' => 'WhatsApp Enquiries', 'slug' => 'whatsapp-enquiries'],
                    ['label' => 'Enquiry Analysis', 'slug' => 'enquiry-analysis'],
                    ['label' => 'Interior Enquiries', 'slug' => 'interior-enquiries'],
                ],
            ],
            [
                'label' => 'Seller Management',
                'key' => 'seller-management',
                'items' => [
                    ['label' => 'Registered Vendors', 'slug' => 'registered-vendors'],
                    ['label' => 'Add Vendor', 'slug' => 'add-vendor'],
                    ['label' => 'Member Requests', 'slug' => 'member-requests'],
                    
                ],
            ],
            [
                'label' => 'Settings',
                'key' => 'settings',
                'items' => [
                    ['label' => 'Banner', 'slug' => 'banner'],
                    ['label' => 'Our Partners', 'slug' => 'our-partners'],
                    ['label' => 'Interior', 'slug' => 'interior'],
                ],
            ],
        ];
    }

    private function profileMenuGroups(): array
    {
        return [
            [
                'label' => 'Admin Menu',
                'key' => 'admin-menu',
                'items' => [
                    ['label' => 'Edit Profile', 'slug' => 'edit-profile'],
                    ['label' => 'Change Password', 'slug' => 'change-password'],
                    ['label' => 'Website Settings', 'slug' => 'website-settings'],
                ],
            ],
        ];
    }

    private function findMenuItem(array $menuGroups, string $slug): ?array
    {
        foreach ($menuGroups as $group) {
            foreach ($group['items'] as $item) {
                if ($item['slug'] === $slug) {
                    return $item + ['group' => $group['key']];
                }
            }
        }

        return null;
    }

    private function resolvePropertiesConnection(): ?string
    {
        $default = config('database.default');

        try {
            if (Schema::connection($default)->hasTable('properties')) {
                return $default;
            }
        } catch (Throwable) {
            // Try explicit MySQL fallback below.
        }

        $env = @parse_ini_file(base_path('.env'), false, INI_SCANNER_RAW) ?: [];
        $dbName = $env['DB_DATABASE'] ?? null;
        $dbUser = $env['DB_USERNAME'] ?? null;
        $dbHost = $env['DB_HOST'] ?? '127.0.0.1';
        $dbPort = $env['DB_PORT'] ?? '3306';

        if (! $dbName || ! $dbUser) {
            return null;
        }

        $connectionName = 'mysql_properties_fallback';
        config([
            "database.connections.$connectionName" => [
                'driver' => 'mysql',
                'host' => $dbHost,
                'port' => $dbPort,
                'database' => $dbName,
                'username' => $dbUser,
                'password' => $env['DB_PASSWORD'] ?? '',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
            ],
        ]);

        DB::purge($connectionName);

        try {
            return Schema::connection($connectionName)->hasTable('properties') ? $connectionName : null;
        } catch (Throwable) {
            return null;
        }
    }
}
