<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            $data['amenities'] = \App\Models\Amenity::orderBy('serial_number')->get();
            $data['categories'] = \App\Models\Category::orderBy('serial_number')->get();
            $data['topPicksCategories'] = [];
            if (DB::getSchemaBuilder()->hasTable('top_picks')) {
                $data['topPicksCategories'] = DB::table('top_picks')->where('status', 1)->get();
            }
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
        $brochureRule = ['nullable', 'file', 'mimes:pdf', 'max:10240'];
        $galleryRule = $isEdit ? ['nullable', 'array'] : ['required', 'array', 'min:1'];

        return $request->validate([
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
        ]);
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
            foreach ($request->file('floor_plan_images', []) as $floorPlanImage) {
                $floorRows[] = [
                    'property_id' => $propertyId,
                    'file' => $floorPlanImage->store('properties/floor-plans', 'public'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (!empty($floorRows)) {
                DB::table('property_floor_plan')->insert($floorRows);
            }
        }

        // sync top picks
        if (DB::getSchemaBuilder()->hasTable('property_top_picks')) {
            DB::table('property_top_picks')->where('property_id', $propertyId)->delete();
            $topPickRows = collect($validated['top_picks'] ?? [])
                ->unique()
                ->map(fn ($tpId) => [
                    'property_id' => $propertyId,
                    'top_pick_id' => $tpId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->values()->all();
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
    }
}
