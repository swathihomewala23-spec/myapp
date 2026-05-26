<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class VendorDashboardController extends Controller
{
    public function index()
    {
        return $this->section('dashboard');
    }

    public function section($section)
    {
        $vendor = Auth::guard('vendor')->user();
        
        $data = [
            'vendor' => $vendor,
            'currentSection' => $section
        ];

        if ($section === 'dashboard') {
            $data['propertiesCount'] = 0;
            $data['enquiriesCount'] = 0;
            
            if (DB::getSchemaBuilder()->hasTable('properties')) {
                $data['propertiesCount'] = DB::table('properties')->where('vendor_id', $vendor->id)->count();
            }
            
            if (DB::getSchemaBuilder()->hasTable('property_enquiries') && DB::getSchemaBuilder()->hasTable('properties')) {
                $data['enquiriesCount'] = DB::table('property_enquiries')
                    ->join('properties', 'property_enquiries.property_id', '=', 'properties.id')
                    ->where('properties.vendor_id', $vendor->id)
                    ->count();
            }
        } elseif ($section === 'manage-properties') {
            $data['properties'] = collect();
            if (DB::getSchemaBuilder()->hasTable('properties')) {
                $query = DB::table('properties')
                    ->where('vendor_id', $vendor->id)
                    ->orderBy('id', 'desc');

                if (DB::getSchemaBuilder()->hasTable('property_categories')) {
                    $query->leftJoin('property_categories', 'properties.category_id', '=', 'property_categories.id')
                          ->select('properties.*', 'property_categories.name as category_name');
                } else {
                    $query->select('properties.*');
                }
                
                $data['properties'] = $query->paginate(15);
            }
        } elseif ($section === 'property-enquiries') {
            $data['enquiries'] = collect();
            if (DB::getSchemaBuilder()->hasTable('property_enquiries') && DB::getSchemaBuilder()->hasTable('properties')) {
                $data['enquiries'] = DB::table('property_enquiries')
                    ->join('properties', 'property_enquiries.property_id', '=', 'properties.id')
                    ->select('property_enquiries.*', 'properties.property_name')
                    ->where('properties.vendor_id', $vendor->id)
                    ->orderBy('property_enquiries.id', 'desc')
                    ->paginate(15);
            }
        } elseif ($section === 'choose-property-type') {
            // No extra data needed
        } elseif ($section === 'add-property') {
            $type = request('type', 'residential');
            $data['selectedPropertyType'] = $type;
            $data['countries'] = \App\Models\Country::orderBy('name')->get();
            $data['states'] = \App\Models\State::with('country')->orderBy('name')->get();
            $data['cities'] = \App\Models\City::with(['country', 'state'])->orderBy('name')->get();
            $data['propertyPlaces'] = \App\Models\PropertyPlace::orderBy('name')->get();
            $data['amenities'] = $this->activeAmenities();
            $data['categories'] = \App\Models\Category::orderBy('serial_number')->get();
            $data['topPicksCategories'] = $this->activeTopPicks();
            $data['bhkOptions'] = $this->bhkOptions();
        }

        return view('vendor-dashboard', $data);
    }

    public function updateProfile(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:vendors,email,' . $vendor->id,
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'details' => 'nullable|string|max:1000',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('vendor_photos', 'public');
        }

        $vendor->update($validated);

        return redirect()->back()->with('status', 'Profile updated successfully.');
    }


        /**
         * Partial update for property (quick edits: name, category, status, area)
         */
        public function updatePropertyPartial(Request $request, $propertyId)
        {
            $vendor = Auth::guard('vendor')->user();

            $property = DB::table('properties')->where('id', $propertyId)->first();
            if (!$property) {
                return response()->json(['error' => 'Property not found.'], 404);
            }
            if (($property->vendor_id ?? null) != ($vendor->id ?? null)) {
                return response()->json(['error' => 'Unauthorized.'], 403);
            }

            $data = $request->only(['property_name', 'category_id', 'status', 'property_area']);

            $validator = \Validator::make($data, [
                'property_name' => ['sometimes', 'required', 'string', 'max:255'],
                'category_id' => ['sometimes', 'nullable', 'integer', 'exists:categories,id'],
                'status' => ['sometimes', 'required', 'in:0,1'],
                'property_area' => ['sometimes', 'nullable', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()], 422);
            }

            $update = [];
            if ($request->has('property_name')) $update['property_name'] = $request->input('property_name');
            if ($request->has('category_id')) $update['category_id'] = $request->input('category_id');
            if ($request->has('status')) $update['status'] = $request->input('status');
            if ($request->has('property_area')) $update['property_area'] = $request->input('property_area');

            if (!empty($update)) {
                DB::table('properties')->where('id', $propertyId)->update($update);
            }

            return response()->json(['status' => 'ok', 'updated' => $update]);
        }
    public function updatePassword(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $vendor->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'The provided current password does not match our records.',
            ]);
        }

        $vendor->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('status', 'Password updated successfully.');
    }

    public function storeProperty(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();
        
        // Replicate Admin validation
        $validated = $this->validatePropertyPayload($request);
        
        $locationName = DB::table('property_places')->where('id', $validated['property_place_id'])->value('name');
        $mainImagePath = $request->file('display_image')?->store('properties/display', 'public');
        $brochurePath = $request->file('brochure')?->store('properties/brochures', 'public');

        $propertyId = DB::table('properties')->insertGetId([
            'property_name' => $validated['property_name'],
            'category_id' => $validated['category_id'],
            'full_address' => $validated['full_address'],
            'min_price' => $validated['min_price'] ?? null,
            'max_price' => $validated['max_price'] ?? null,
            'average_price' => $this->averagePrice($validated['min_price'] ?? null, $validated['max_price'] ?? null),
            'vendor_id' => $vendor->id,
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
            'approve_status' => 2, // pending approval from admin
            'new_launched' => ($validated['new_launched'] ?? null) === 'yes' ? 'yes' : 'no',
            'elite_project' => ($validated['elite_project'] ?? null) === 'yes' ? 'yes' : 'no',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->syncPropertyExtraData($propertyId, $request, $validated);

        return redirect()->route('vendor.section', 'manage-properties')->with('status', 'Property successfully created and is pending Admin Approval!');
    }

    private function validatePropertyPayload(Request $request, bool $isEdit = false): array
    {
        $displayImageRule = $isEdit ? ['nullable', 'image', 'mimes:png,jpg,jpeg,avif', 'max:2048'] : ['required', 'image', 'mimes:png,jpg,jpeg,avif', 'max:2048'];
        $brochureRule = $isEdit ? ['nullable', 'file', 'mimes:pdf', 'max:10240'] : ['required', 'file', 'mimes:pdf', 'max:10240'];
        $galleryRule = $isEdit ? ['nullable', 'array'] : ['required', 'array', 'min:1'];

        $bhkOptions = $this->bhkOptions();

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
            'top_picks.*' => ['integer', 'exists:top_picks,id'],
            'elite_project' => ['nullable', 'in:yes'],
            'new_launched' => ['nullable', 'in:yes'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            'bhk' => ['nullable', 'array'],
            'bhk.*' => ['string', Rule::in($bhkOptions)],
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
            'brochure.required' => 'Please upload the property brochure.',
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

    private function normalizeFurnishedStatus(string $status): string
    {
        return match ($status) {
            'Furnished' => 'Furnished',
            'Semi-furnished' => 'Semi-furnished',
            default => 'Unfurnished',
        };
    }

    private function syncPropertyExtraData(int $propertyId, Request $request, array $validated, bool $isUpdate = false): void
    {
        // sync amenities
        if (DB::getSchemaBuilder()->hasTable('property_amenities')) {
            DB::table('property_amenities')->where('property_id', $propertyId)->delete();
            $amenityRows = collect($validated['amenity_ids'] ?? [])
                ->unique()
                ->map(fn ($amenityId) => [
                    'property_id' => $propertyId,
                    'amenity_id' => $amenityId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->values()->all();
            if (!empty($amenityRows)) {
                DB::table('property_amenities')->insert($amenityRows);
            }
        }

        // sync gallery
        if (DB::getSchemaBuilder()->hasTable('property_slider_images')) {
            if ($isUpdate && $request->hasFile('gallery_images')) {
                DB::table('property_slider_images')->where('property_id', $propertyId)->delete();
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
                DB::table('property_slider_images')->insert($galleryRows);
            }
        }

        // sync floor plans
        if (DB::getSchemaBuilder()->hasTable('property_floor_plan')) {
            if ($isUpdate && $request->hasFile('floor_plan_images')) {
                DB::table('property_floor_plan')->where('property_id', $propertyId)->delete();
            }
            $floorRows = [];
            $firstFloorPlan = null;
            foreach ($request->file('floor_plan_images', []) as $floorPlanImage) {
                $storedFloorPlan = $floorPlanImage->store('properties/floor-plans', 'public');
                $firstFloorPlan ??= $storedFloorPlan;
                $floorRows[] = [
                    'property_id' => $propertyId,
                    'file' => $storedFloorPlan,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (!empty($floorRows)) {
                DB::table('property_floor_plan')->insert($floorRows);
            }
            if ($firstFloorPlan && DB::getSchemaBuilder()->hasColumn('properties', 'floor_plan_image')) {
                DB::table('properties')->where('id', $propertyId)->update(['floor_plan_image' => $firstFloorPlan]);
            }
        }

        if (DB::getSchemaBuilder()->hasTable('property_brochures')) {
            DB::table('property_brochures')->where('property_id', $propertyId)->delete();
            $brochureFile = DB::table('properties')->where('id', $propertyId)->value('brochure');

            if ($brochureFile) {
                DB::table('property_brochures')->insert([
                    'property_id' => $propertyId,
                    'file' => $brochureFile,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // sync top picks
        if (DB::getSchemaBuilder()->hasTable('property_top_picks')) {
            DB::table('property_top_picks')->where('property_id', $propertyId)->delete();
            $topPickColumn = DB::getSchemaBuilder()->hasColumn('property_top_picks', 'top_picks_id') ? 'top_picks_id' : 'top_pick_id';
            $hasCreatedAt = DB::getSchemaBuilder()->hasColumn('property_top_picks', 'created_at');
            $hasUpdatedAt = DB::getSchemaBuilder()->hasColumn('property_top_picks', 'updated_at');
            $hasCreatedDate = DB::getSchemaBuilder()->hasColumn('property_top_picks', 'created_date');
            $hasUpdatedDate = DB::getSchemaBuilder()->hasColumn('property_top_picks', 'updated_date');

            $topPickRows = collect($validated['top_picks'] ?? [])
                ->unique()
                ->map(function ($tpId) use ($propertyId, $topPickColumn, $hasCreatedAt, $hasUpdatedAt, $hasCreatedDate, $hasUpdatedDate) {
                    $row = [
                        'property_id' => $propertyId,
                        $topPickColumn => $tpId,
                    ];

                    if ($hasCreatedAt) {
                        $row['created_at'] = now();
                    }

                    if ($hasUpdatedAt) {
                        $row['updated_at'] = now();
                    }

                    if ($hasCreatedDate) {
                        $row['created_date'] = now();
                    }

                    if ($hasUpdatedDate) {
                        $row['updated_date'] = now();
                    }

                    return $row;
                })->values()->all();
            if (!empty($topPickRows)) {
                DB::table('property_top_picks')->insert($topPickRows);
            }
        }

        // sync faqs
        if (DB::getSchemaBuilder()->hasTable('property_faq')) {
            DB::table('property_faq')->where('property_id', $propertyId)->delete();
            $faqRows = collect($request->input('faqs', []))
                ->filter(fn ($f) => !empty($f['question']) && !empty($f['answer']))
                ->map(fn ($f) => [
                    'property_id' => $propertyId,
                    'question' => $f['question'],
                    'answer' => $f['answer'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->values()->all();
            if (!empty($faqRows)) {
                DB::table('property_faq')->insert($faqRows);
            }
        }

        if (DB::getSchemaBuilder()->hasTable('property_contents')) {
            $languageId = $this->resolveDefaultLanguageId();

            if ($languageId) {
                $payload = [
                    'property_id' => $propertyId,
                    'language_id' => $languageId,
                    'title' => $validated['property_name'],
                    'slug' => Str::slug($validated['property_name']) . '-' . $propertyId,
                    'address' => $validated['full_address'],
                    'description' => $validated['overview'],
                    'meta_keyword' => $validated['rera_number'] ?? null,
                    'meta_description' => $validated['map_selected'] ?? null,
                    'updated_at' => now(),
                ];

                $existing = DB::table('property_contents')
                    ->where('property_id', $propertyId)
                    ->where('language_id', $languageId)
                    ->first();

                if ($existing) {
                    DB::table('property_contents')->where('id', $existing->id)->update($payload);
                } else {
                    DB::table('property_contents')->insert($payload + ['created_at' => now()]);
                }
            }
        }
    }

    private function averagePrice($minPrice, $maxPrice): ?float
    {
        $prices = collect([$minPrice, $maxPrice])
            ->filter(fn ($price) => $price !== null && $price !== '')
            ->map(fn ($price) => (float) $price)
            ->values();

        if ($prices->isEmpty()) {
            return null;
        }

        return round($prices->avg(), 2);
    }

    private function resolveDefaultLanguageId(): ?int
    {
        if (! DB::getSchemaBuilder()->hasTable('languages')) {
            return null;
        }

        $languageId = DB::table('languages')->where('is_default', 1)->value('id');
        if ($languageId) {
            return (int) $languageId;
        }

        $fallbackLanguageId = DB::table('languages')->orderBy('id')->value('id');
        return $fallbackLanguageId ? (int) $fallbackLanguageId : null;
    }

    private function activeAmenities()
    {
        $query = \App\Models\Amenity::query();

        if (DB::getSchemaBuilder()->hasColumn('amenities', 'status')) {
            $query->where('status', 1);
        }

        if (DB::getSchemaBuilder()->hasColumn('amenities', 'serial_number')) {
            $query->orderBy('serial_number');
        }

        return $query->orderBy('name')->get();
    }

    private function activeTopPicks()
    {
        if (! DB::getSchemaBuilder()->hasTable('top_picks')) {
            return collect();
        }

        $query = DB::table('top_picks');

        if (DB::getSchemaBuilder()->hasColumn('top_picks', 'status')) {
            $query->where('status', 1);
        }

        return $query->orderBy('id')->get();
    }

    private function bhkOptions(): array
    {
        $defaults = ['1 BHK', '2 BHK', '3 BHK', '4 BHK', '5+ BHK'];

        if (! DB::getSchemaBuilder()->hasTable('properties') || ! DB::getSchemaBuilder()->hasColumn('properties', 'bhk')) {
            return $defaults;
        }

        $existing = DB::table('properties')
            ->whereNotNull('bhk')
            ->pluck('bhk')
            ->flatMap(function ($value) {
                $decoded = json_decode($value, true);

                if (is_array($decoded)) {
                    return $decoded;
                }

                return array_filter(array_map('trim', explode(',', (string) $value)));
            })
            ->filter()
            ->map(fn ($value) => (string) $value)
            ->all();

        return collect($defaults)
            ->merge($existing)
            ->unique()
            ->values()
            ->all();
    }

    public function editProperty($propertyId)
    {
        $vendor = Auth::guard('vendor')->user();
        
        if (!DB::getSchemaBuilder()->hasTable('properties')) {
            return redirect()->route('vendor.section', 'manage-properties')->with('error', 'Property not found.');
        }

        $property = DB::table('properties')->where('id', $propertyId)->where('vendor_id', $vendor->id)->first();
        
        if (!$property) {
            return redirect()->route('vendor.section', 'manage-properties')->with('error', 'Property not found or unauthorized.');
        }

        $propertyContent = null;
        $propertyFaqs = [];
        $propertyPlaceId = \App\Models\PropertyPlace::where('name', $property->property_area)->value('id');

        if (DB::getSchemaBuilder()->hasTable('property_contents')) {
            $propertyContent = DB::table('property_contents')
                ->where('property_id', $propertyId)
                ->orderByDesc('id')
                ->first();
        }

        if (DB::getSchemaBuilder()->hasTable('property_faq')) {
            $propertyFaqs = DB::table('property_faq')
                ->where('property_id', $propertyId)
                ->select('question', 'answer')
                ->orderBy('id')
                ->limit(25)
                ->get()
                ->toArray();
        }

        $data = [
            'vendor' => $vendor,
            'currentSection' => 'edit-property',
            'property' => $property,
            'propertyContent' => $propertyContent,
            'propertyFaqs' => $propertyFaqs,
            'propertyPlaceId' => $propertyPlaceId,
            'countries' => \App\Models\Country::orderBy('name')->get(),
            'states' => \App\Models\State::with('country')->orderBy('name')->get(),
            'cities' => \App\Models\City::with(['country', 'state'])->orderBy('name')->get(),
            'propertyPlaces' => \App\Models\PropertyPlace::orderBy('name')->get(),
            'amenities' => $this->activeAmenities(),
            'categories' => \App\Models\Category::orderBy('serial_number')->get(),
            'topPicksCategories' => $this->activeTopPicks(),
            'bhkOptions' => $this->bhkOptions(),
        ];

        return view('vendor-dashboard', $data);
    }

    public function updateProperty(Request $request, $propertyId)
    {
        $vendor = Auth::guard('vendor')->user();
        
        if (!DB::getSchemaBuilder()->hasTable('properties')) {
            return redirect()->route('vendor.section', 'manage-properties')->with('error', 'Property not found.');
        }

        $property = DB::table('properties')->where('id', $propertyId)->where('vendor_id', $vendor->id)->first();
        
        if (!$property) {
            return redirect()->route('vendor.section', 'manage-properties')->with('error', 'Property not found or unauthorized.');
        }

        $validated = $this->validatePropertyPayload($request, true);
        
        $locationName = DB::table('property_places')->where('id', $validated['property_place_id'])->value('name');
        
        $updateData = [
            'property_name' => $validated['property_name'],
            'category_id' => $validated['category_id'],
            'full_address' => $validated['full_address'],
            'min_price' => $validated['min_price'] ?? null,
            'max_price' => $validated['max_price'] ?? null,
            'average_price' => $this->averagePrice($validated['min_price'] ?? null, $validated['max_price'] ?? null),
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
            'status' => (int) $validated['status'],
            'new_launched' => ($validated['new_launched'] ?? null) === 'yes' ? 'yes' : 'no',
            'elite_project' => ($validated['elite_project'] ?? null) === 'yes' ? 'yes' : 'no',
            'updated_at' => now(),
        ];

        if ($request->hasFile('display_image')) {
            $mainImagePath = $request->file('display_image')->store('properties/display', 'public');
            $updateData['main_property_image'] = $mainImagePath;
            $updateData['display_image'] = $mainImagePath;
        }

        if ($request->hasFile('brochure')) {
            $brochurePath = $request->file('brochure')->store('properties/brochures', 'public');
            $updateData['brochure'] = $brochurePath;
        }

        DB::table('properties')->where('id', $propertyId)->update($updateData);
        
        $this->syncPropertyExtraData($propertyId, $request, $validated, true);

        return redirect()->route('vendor.section', 'manage-properties')->with('status', 'Property successfully updated!');
    }

    public function destroyProperty($propertyId)
    {
        $vendor = Auth::guard('vendor')->user();
        
        if (!DB::getSchemaBuilder()->hasTable('properties')) {
            return redirect()->route('vendor.section', 'manage-properties')->with('error', 'Property not found.');
        }

        $property = DB::table('properties')->where('id', $propertyId)->where('vendor_id', $vendor->id)->first();
        
        if (!$property) {
            return redirect()->route('vendor.section', 'manage-properties')->with('error', 'Property not found or unauthorized.');
        }

        DB::table('properties')->where('id', $propertyId)->delete();

        // Clean up related data
        if (DB::getSchemaBuilder()->hasTable('property_amenities')) {
            DB::table('property_amenities')->where('property_id', $propertyId)->delete();
        }
        if (DB::getSchemaBuilder()->hasTable('property_slider_images')) {
            DB::table('property_slider_images')->where('property_id', $propertyId)->delete();
        }
        if (DB::getSchemaBuilder()->hasTable('property_floor_plan')) {
            DB::table('property_floor_plan')->where('property_id', $propertyId)->delete();
        }
        if (DB::getSchemaBuilder()->hasTable('property_brochures')) {
            DB::table('property_brochures')->where('property_id', $propertyId)->delete();
        }
        if (DB::getSchemaBuilder()->hasTable('property_top_picks')) {
            DB::table('property_top_picks')->where('property_id', $propertyId)->delete();
        }
        if (DB::getSchemaBuilder()->hasTable('property_faq')) {
            DB::table('property_faq')->where('property_id', $propertyId)->delete();
        }
        if (DB::getSchemaBuilder()->hasTable('property_contents')) {
            DB::table('property_contents')->where('property_id', $propertyId)->delete();
        }

        return redirect()->route('vendor.section', 'manage-properties')->with('status', 'Property successfully deleted!');
    }
}
