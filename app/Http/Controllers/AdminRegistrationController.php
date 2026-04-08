<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class AdminRegistrationController extends Controller
{
    public function create(): View
    {
        return view('admin-register');
    }

    public function store(Request $request): RedirectResponse
    {
        $hasUsernameColumn = Schema::hasColumn('users', 'username');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
        ];

        $rules['username'] = $hasUsernameColumn
            ? ['nullable', 'string', 'max:255', 'unique:users,username']
            : ['nullable', 'string', 'max:255'];

        $validated = $request->validate($rules, [
            'username.unique' => 'This username is already taken. Please choose another username.',
        ]);

        $username = filled($validated['username'] ?? null) ? $validated['username'] : null;
        $emailHandle = $username ?: Str::slug($validated['name'], '.') . '.' . Str::lower(Str::random(8));
        $generatedEmail = Str::lower($emailHandle) . '@admin.local';
        $email = filled($validated['email'] ?? null) ? Str::lower($validated['email']) : $generatedEmail;

        $payload = [
            'email' => $email,
            'password' => $validated['password'],
        ];

        if (Schema::hasColumn('users', 'name')) {
            $payload['name'] = $validated['name'];
        }

        if ($hasUsernameColumn) {
            $payload['username'] = $username;
        }

        if (Schema::hasColumn('users', 'first_name')) {
            $payload['first_name'] = $validated['name'];
        }

        if (Schema::hasColumn('users', 'contact_number')) {
            $payload['contact_number'] = $validated['contact_number'] ?: null;
        }

        if (Schema::hasColumn('users', 'phone')) {
            $payload['phone'] = $validated['contact_number'] ?: null;
        }

        if (Schema::hasColumn('users', 'status')) {
            $payload['status'] = 1;
        }

        $user = User::create($payload);

        return redirect()
            ->route('admin.dashboard', $user)
            ->with('status', 'Admin account created successfully.');
    }

    public function dashboard(User $user): View
    {
        return view('admin-dashboard', $this->dashboardViewData($user));
    }

    public function choosePropertyType(User $user): View
    {
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

    public function createPropertyForm(User $user, string $type): View
    {
        $normalizedType = Str::lower($type);

        abort_unless(in_array($normalizedType, ['residential', 'commercial'], true), 404);

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
            ]
        ));
    }

    public function section(User $user, string $section): View
    {
        $menu = array_merge($this->menuGroups(), $this->profileMenuGroups());
        $currentItem = $this->findMenuItem($menu, $section);

        $data = [
            'currentPage' => $currentItem['slug'],
            'currentGroup' => $currentItem['group'],
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
        } elseif ($section === 'manage-properties') {
            $connection = $this->resolvePropertiesConnection();

            if (! $connection || ! Schema::connection($connection)->hasTable('properties')) {
                $data['manageProperties'] = collect();
            } else {
                $query = DB::connection($connection)->table('properties')
                    ->select(
                        'properties.id',
                        'properties.property_name',
                        'properties.type',
                        'properties.city',
                        'properties.status',
                        'properties.approve_status',
                        'properties.new_launched',
                        'properties.elite_project',
                        'properties.created_at'
                    )
                    ->orderByDesc('properties.id');

                if (Schema::connection($connection)->hasTable('property_contents')) {
                    $query->leftJoin('property_contents', 'property_contents.property_id', '=', 'properties.id')
                        ->addSelect('property_contents.title as content_title');
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

                    return $property;
                });
            }

        } elseif ($section === 'registered-users') {
            $data['registeredUsers'] = User::orderBy('id', 'desc')->get();
        }

        return view('admin-dashboard', array_merge(
            $this->dashboardViewData($user),
            $data
        ));
    }

    public function storeAmenity(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string',
            'status' => 'required|string',
            'serial_number' => 'required|integer',
        ]);

        \App\Models\Amenity::create([
            'name' => $validated['name'],
            'icon' => $validated['icon'],
            'status' => $validated['status'],
            'serial_number' => $validated['serial_number'],
        ]);

        return redirect()
            ->route('admin.section', ['user' => $user, 'section' => 'amenities'])
            ->with('status', 'Amenity added successfully!');
    }

    public function updateAmenity(Request $request, User $user, \App\Models\Amenity $amenity): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string',
            'status' => 'required|string',
            'serial_number' => 'required|integer',
        ]);

        $amenity->update([
            'name' => $validated['name'],
            'icon' => $validated['icon'],
            'status' => $validated['status'],
            'serial_number' => $validated['serial_number'],
        ]);
        return redirect()
            ->route('admin.section', ['user' => $user, 'section' => 'amenities'])
            ->with('status', 'Amenity updated successfully!');
    }

    public function destroyAmenity(User $user, \App\Models\Amenity $amenity): RedirectResponse
    {
        $amenity->delete();

        return redirect()
            ->route('admin.section', ['user' => $user, 'section' => 'amenities'])
            ->with('status', 'Amenity deleted successfully!');
    }

    public function storeCategory(Request $request, User $user): RedirectResponse
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
            'status' => $validated['status'],
            'serial_number' => $validated['serial_number'],
        ]);

        return redirect()
            ->route('admin.section', ['user' => $user, 'section' => 'categories'])
            ->with('status', 'Category added successfully!');
    }

    public function updateCategory(Request $request, User $user, \App\Models\Category $category): RedirectResponse
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
            'status' => $validated['status'],
            'serial_number' => $validated['serial_number'],
        ]);

        return redirect()
            ->route('admin.section', ['user' => $user, 'section' => 'categories'])
            ->with('status', 'Category updated successfully!');
    }

    public function destroyCategory(User $user, \App\Models\Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()
            ->route('admin.section', ['user' => $user, 'section' => 'categories'])
            ->with('status', 'Category deleted successfully!');
    }

    public function storeCountry(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        \App\Models\Country::create([
            'name' => $validated['name'],
        ]);

        return redirect()
            ->route('admin.section', ['user' => $user, 'section' => 'countries'])
            ->with('status', 'Country added successfully!');
    }

    public function updateCountry(Request $request, User $user, \App\Models\Country $country): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $country->update([
            'name' => $validated['name'],
        ]);

        return redirect()
            ->route('admin.section', ['user' => $user, 'section' => 'countries'])
            ->with('status', 'Country updated successfully!');
    }

    public function destroyCountry(User $user, \App\Models\Country $country): RedirectResponse
    {
        $country->delete();

        return redirect()
            ->route('admin.section', ['user' => $user, 'section' => 'countries'])
            ->with('status', 'Country deleted successfully!');
    }

    public function storeState(Request $request, User $user): RedirectResponse
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
            ->route('admin.section', ['user' => $user, 'section' => 'states'])
            ->with('status', 'State added successfully!');
    }

    public function updateState(Request $request, User $user, \App\Models\State $state): RedirectResponse
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
            ->route('admin.section', ['user' => $user, 'section' => 'states'])
            ->with('status', 'State updated successfully!');
    }

    public function destroyState(User $user, \App\Models\State $state): RedirectResponse
    {
        $state->delete();

        return redirect()
            ->route('admin.section', ['user' => $user, 'section' => 'states'])
            ->with('status', 'State deleted successfully!');
    }

    public function storeCity(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:property_countries,id',
            'state_id' => 'required|exists:property_states,id',
        ]);

        \App\Models\City::create([
            'name' => $validated['name'],
            'country_id' => $validated['country_id'],
            'state_id' => $validated['state_id'],
        ]);

        return redirect()
            ->route('admin.section', ['user' => $user, 'section' => 'cities'])
            ->with('status', 'City added successfully!');
    }

    public function updateCity(Request $request, User $user, \App\Models\City $city): RedirectResponse
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
            ->route('admin.section', ['user' => $user, 'section' => 'cities'])
            ->with('status', 'City updated successfully!');
    }

    public function destroyCity(User $user, \App\Models\City $city): RedirectResponse
    {
        $city->delete();

        return redirect()
            ->route('admin.section', ['user' => $user, 'section' => 'cities'])
            ->with('status', 'City deleted successfully!');
    }

    public function storePropertyPlace(Request $request, User $user): RedirectResponse
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
            ->route('admin.section', ['user' => $user, 'section' => 'property-places'])
            ->with('status', 'Property Place added successfully!');
    }

    public function editPropertyPlace(User $user, \App\Models\PropertyPlace $property_place): View
    {
        return view('admin-dashboard', array_merge(
            $this->dashboardViewData($user),
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

    public function updatePropertyPlace(Request $request, User $user, \App\Models\PropertyPlace $property_place): RedirectResponse
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
            ->route('admin.section', ['user' => $user, 'section' => 'property-places'])
            ->with('status', 'Property Place updated successfully!');
    }

    public function destroyPropertyPlace(User $user, \App\Models\PropertyPlace $property_place): RedirectResponse
    {
        $property_place->delete();

        return redirect()
            ->route('admin.section', ['user' => $user, 'section' => 'property-places'])
            ->with('status', 'Property Place deleted successfully!');
    }

    public function updateUser(Request $request, User $user, User $targetUser): RedirectResponse
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
            ->route('admin.section', ['user' => $user, 'section' => 'registered-users'])
            ->with('status', 'User updated successfully!');
    }

    public function toggleUserStatus(User $user, User $targetUser): RedirectResponse
    {
        if (!Schema::hasColumn('users', 'status')) {
            return redirect()
                ->route('admin.section', ['user' => $user, 'section' => 'registered-users'])
                ->with('status', 'Account status field unavailable.');
        }

        $targetUser->status = ($targetUser->status == 'active' || $targetUser->status == 1) ? 0 : 1;
        $targetUser->save();

        return redirect()
            ->route('admin.section', ['user' => $user, 'section' => 'registered-users'])
            ->with('status', 'User status updated successfully!');
    }

    public function toggleEmailVerification(User $user, User $targetUser): RedirectResponse
    {
        if (!Schema::hasColumn('users', 'email_verified_at')) {
            return redirect()
                ->route('admin.section', ['user' => $user, 'section' => 'registered-users'])
                ->with('status', 'Email verification field unavailable.');
        }

        $targetUser->email_verified_at = $targetUser->email_verified_at ? null : now();
        $targetUser->save();

        return redirect()
            ->route('admin.section', ['user' => $user, 'section' => 'registered-users'])
            ->with('status', 'Email verification status updated successfully!');
    }

    public function updateEmailVerification(Request $request, User $user, User $targetUser): RedirectResponse
    {
        if (!Schema::hasColumn('users', 'email_verified_at')) {
            return redirect()
                ->route('admin.section', ['user' => $user, 'section' => 'registered-users'])
                ->with('status', 'Email verification field unavailable.');
        }

        $validated = $request->validate(['email_status' => 'required|in:verified,unverified']);
        $targetUser->email_verified_at = $validated['email_status'] === 'verified' ? now() : null;
        $targetUser->save();

        return redirect()
            ->route('admin.section', ['user' => $user, 'section' => 'registered-users'])
            ->with('status', 'Email verification status updated successfully!');
    }

    public function createUser(User $user): View
    {
        return view('user-form', [
            'user' => $user,
            'mode' => 'create',
        ]);
    }

    public function editUser(User $user, User $targetUser): View
    {
        return view('user-form', [
            'user' => $user,
            'targetUser' => $targetUser,
            'mode' => 'edit',
        ]);
    }

    public function storeUser(Request $request, User $user): RedirectResponse
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
            ->route('admin.section', ['user' => $user, 'section' => 'registered-users'])
            ->with('status', 'User added successfully!');
    }

    public function updateUserStatus(Request $request, User $user, User $targetUser): RedirectResponse
    {
        if (!Schema::hasColumn('users', 'status')) {
            return redirect()
                ->route('admin.section', ['user' => $user, 'section' => 'registered-users'])
                ->with('status', 'Account status field unavailable.');
        }

        $validated = $request->validate(['account_status' => 'required|in:active,inactive,1,0']);
        $status = $validated['account_status'];
        $targetUser->status = in_array($status, ['active', 1, '1'], true) ? 1 : 0;
        $targetUser->save();

        return redirect()
            ->route('admin.section', ['user' => $user, 'section' => 'registered-users'])
            ->with('status', 'Account status updated successfully!');
    }

    public function destroyUser(User $user, User $targetUser): RedirectResponse
    {
        if ($user->id === $targetUser->id) {
            return redirect()
                ->route('admin.section', ['user' => $user, 'section' => 'registered-users'])
                ->with('status', 'You cannot delete your own account from here.');
        }

        $targetUser->delete();

        return redirect()
            ->route('admin.section', ['user' => $user, 'section' => 'registered-users'])
            ->with('status', 'User removed successfully!');
    }

    private function dashboardViewData(User $user): array
    {
        return [
            'user' => $user,
            'stats' => [
                [
                    'label' => 'Total User',
                    'value' => '40,689',
                    'change' => '8.5% Up from Last Month',
                    'tone' => 'lavender',
                    'icon' => 'users',
                ],
                [
                    'label' => 'Property Seller',
                    'value' => '60',
                    'change' => '8.5% Up from Last Month',
                    'tone' => 'gold',
                    'icon' => 'box',
                ],
                [
                    'label' => 'Total Properties',
                    'value' => '1000',
                    'change' => '4.3% Down from Last Month',
                    'tone' => 'mint',
                    'icon' => 'chart',
                ],
                [
                    'label' => 'Total Property Enquiries',
                    'value' => '2040',
                    'change' => '8.5% Up from Last Month',
                    'tone' => 'peach',
                    'icon' => 'clock',
                ],
                [
                    'label' => 'Total Interior Enquiries',
                    'value' => '22',
                    'change' => '8.5% Up from Last Month',
                    'tone' => 'peach',
                    'icon' => 'clock',
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
                    ['label' => 'Add User', 'slug' => 'add-user'],
                ],
            ],
            [
                'label' => 'Master Management',
                'key' => 'master-management',
                'items' => [
                    
                    ['label' => 'Chennai Page Pinning First Project', 'slug' => 'chennai-page-pinning-first-project'],
                    ['label' => 'Best Deals Pinning First Project', 'slug' => 'best-deals-pinning-first-project'],
                    ['label' => 'NRI Investment Pinning First Project', 'slug' => 'nri-investment-pinning-first-project'],
                    ['label' => 'Luxury Homes Pinning First Project', 'slug' => 'luxury-homes-pinning-first-project'],
                    ['label' => 'Best Location Picks Pinning First Project', 'slug' => 'best-location-picks-pinning-first-project'],
                    ['label' => 'Apartment Pinning First Project', 'slug' => 'apartment-pinning-first-project'],
                    ['label' => 'Villa Pinning First Project', 'slug' => 'villa-pinning-first-project'],
                    ['label' => 'Plot Pinning First Project', 'slug' => 'plot-pinning-first-project'],
                   
                ],
            ],
            [
                'label' => 'Property Management',
                'key' => 'property-management',
                'items' => [
                    ['label' => 'Manage properties', 'slug' => 'manage-properties'],
                    ['label' => 'Property Enquiries', 'slug' => 'property-enquiries'],
                ],
            ],
            [
                'label' => 'Enquiry Management',
                'key' => 'enquiry-management',
                'items' => [
                    ['label' => 'Analysis', 'slug' => 'analysis'],
                    ['label' => 'Dashboard', 'slug' => 'enquiry-dashboard'],
                    ['label' => 'Property wish', 'slug' => 'property-wish'],
                ],
            ],
            [
                'label' => 'Seller Management',
                'key' => 'seller-management',
                'items' => [
                    ['label' => 'Registered Vendors', 'slug' => 'registered-vendors'],
                    ['label' => 'Member Requests', 'slug' => 'member-requests'],
                ],
            ],
            [
                'label' => 'Settings',
                'key' => 'settings',
                'items' => [
                    ['label' => 'Banner', 'slug' => 'banner'],
                    ['label' => 'Our Partners', 'slug' => 'our-partners'],
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
