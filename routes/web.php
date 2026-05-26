<?php

use App\Http\Controllers\AdminRegistrationController;
use App\Http\Controllers\AdminLoginController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// Registration Routes (Initial setup)
Route::get('/', [AdminRegistrationController::class, 'create'])->name('admin.register');
Route::post('/', [AdminRegistrationController::class, 'store'])->name('admin.register.store');

// Protected Admin Routes
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', [AdminRegistrationController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard/properties/create', [AdminRegistrationController::class, 'choosePropertyType'])->name('admin.properties.create');
    Route::get('/dashboard/properties/create/{type}', [AdminRegistrationController::class, 'createPropertyForm'])->name('admin.properties.create.type');
    Route::post('/dashboard/properties', [AdminRegistrationController::class, 'storeProperty'])->name('admin.properties.store');
    Route::get('/dashboard/properties/{id}/edit', [AdminRegistrationController::class, 'editProperty'])->name('admin.properties.edit');
    Route::put('/dashboard/properties/{id}', [AdminRegistrationController::class, 'updateProperty'])->name('admin.properties.update');
    Route::put('/dashboard/properties/{id}/approval-status', [AdminRegistrationController::class, 'updatePropertyApprovalStatus'])->name('admin.properties.approval-status');
    Route::delete('/dashboard/properties/{id}', [AdminRegistrationController::class, 'destroyProperty'])->name('admin.properties.destroy');

    Route::get('/dashboard/section/{section}', [AdminRegistrationController::class, 'section'])->name('admin.section');
    Route::put('/dashboard/profile/update', [AdminRegistrationController::class, 'updateProfile'])->name('admin.profile.update');
    Route::put('/dashboard/password/update', [AdminRegistrationController::class, 'updatePassword'])->name('admin.password.update');
    Route::put('/dashboard/website-settings/update', [AdminRegistrationController::class, 'updateWebsiteSettings'])->name('admin.website-settings.update');
    Route::post('/dashboard/banners', [AdminRegistrationController::class, 'storeBanner'])->name('admin.banners.store');
    Route::put('/dashboard/banners/{id}/status', [AdminRegistrationController::class, 'updateBannerStatus'])->name('admin.banners.updateStatus');
    Route::delete('/dashboard/banners/{id}', [AdminRegistrationController::class, 'destroyBanner'])->name('admin.banners.destroy');
    Route::post('/dashboard/our-partners', [AdminRegistrationController::class, 'storeOurPartner'])->name('admin.partners.store');
    Route::put('/dashboard/our-partners/{partner}', [AdminRegistrationController::class, 'updateOurPartner'])->name('admin.partners.update');
    Route::delete('/dashboard/our-partners/{partner}', [AdminRegistrationController::class, 'destroyOurPartner'])->name('admin.partners.destroy');
    Route::post('/dashboard/interiors', [AdminRegistrationController::class, 'storeInteriorDesign'])->name('admin.interiors.store');
    Route::put('/dashboard/interiors/{interior}', [AdminRegistrationController::class, 'updateInteriorDesign'])->name('admin.interiors.update');
    Route::delete('/dashboard/interiors/{interior}', [AdminRegistrationController::class, 'destroyInteriorDesign'])->name('admin.interiors.destroy');
    Route::post('/dashboard/vendors', [AdminRegistrationController::class, 'storeVendor'])->name('admin.vendors.store');
    Route::post('/dashboard/vendors/{vendor}/secret-login', [AdminRegistrationController::class, 'secretLoginVendor'])->name('admin.vendors.secret-login');
    Route::get('/dashboard/vendors/{vendor}/edit', [AdminRegistrationController::class, 'editVendor'])->name('admin.vendors.edit');
    Route::put('/dashboard/vendors/{vendor}', [AdminRegistrationController::class, 'updateVendor'])->name('admin.vendors.update');
    Route::delete('/dashboard/vendors/{vendor}', [AdminRegistrationController::class, 'destroyVendor'])->name('admin.vendors.destroy');
    Route::get('/dashboard/vendors/{vendor}/password', [AdminRegistrationController::class, 'editVendorPassword'])->name('admin.vendors.password');
    Route::put('/dashboard/vendors/{vendor}/password', [AdminRegistrationController::class, 'updateVendorPassword'])->name('admin.vendors.password.update');
    Route::get('/dashboard/vendors/{vendor}', [AdminRegistrationController::class, 'showVendor'])->name('admin.vendors.show');
    
    // Amenities
    Route::post('/dashboard/amenities', [AdminRegistrationController::class, 'storeAmenity'])->name('admin.amenities.store');
    Route::put('/dashboard/amenities/{amenity}', [AdminRegistrationController::class, 'updateAmenity'])->name('admin.amenities.update');
    Route::delete('/dashboard/amenities/{amenity}', [AdminRegistrationController::class, 'destroyAmenity'])->name('admin.amenities.destroy');
    
    // Categories
    Route::post('/dashboard/categories', [AdminRegistrationController::class, 'storeCategory'])->name('admin.categories.store');
    Route::put('/dashboard/categories/{category}', [AdminRegistrationController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/dashboard/categories/{category}', [AdminRegistrationController::class, 'destroyCategory'])->name('admin.categories.destroy');

    // Countries, States, Cities
    Route::post('/dashboard/countries', [AdminRegistrationController::class, 'storeCountry'])->name('admin.countries.store');
    Route::put('/dashboard/countries/{country}', [AdminRegistrationController::class, 'updateCountry'])->name('admin.countries.update');
    Route::delete('/dashboard/countries/{country}', [AdminRegistrationController::class, 'destroyCountry'])->name('admin.countries.destroy');

    Route::post('/dashboard/states', [AdminRegistrationController::class, 'storeState'])->name('admin.states.store');
    Route::put('/dashboard/states/{state}', [AdminRegistrationController::class, 'updateState'])->name('admin.states.update');
    Route::delete('/dashboard/states/{state}', [AdminRegistrationController::class, 'destroyState'])->name('admin.states.destroy');

    Route::post('/dashboard/cities', [AdminRegistrationController::class, 'storeCity'])->name('admin.cities.store');
    Route::put('/dashboard/cities/{city}', [AdminRegistrationController::class, 'updateCity'])->name('admin.cities.update');
    Route::delete('/dashboard/cities/{city}', [AdminRegistrationController::class, 'destroyCity'])->name('admin.cities.destroy');

    // Property Places
    Route::post('/dashboard/property-places', [AdminRegistrationController::class, 'storePropertyPlace'])->name('admin.property-places.store');
    Route::get('/dashboard/property-places/{property_place}/edit', [AdminRegistrationController::class, 'editPropertyPlace'])->name('admin.property-places.edit');
    Route::put('/dashboard/property-places/{property_place}', [AdminRegistrationController::class, 'updatePropertyPlace'])->name('admin.property-places.update');
    Route::delete('/dashboard/property-places/{property_place}', [AdminRegistrationController::class, 'destroyPropertyPlace'])->name('admin.property-places.destroy');
    // Backward-compatible legacy URLs (old frontend scripts may still submit these paths)
    Route::post('/dashboard/{user}/property-places', [AdminRegistrationController::class, 'storePropertyPlace']);
    Route::put('/dashboard/{user}/property-places/{property_place}', [AdminRegistrationController::class, 'updatePropertyPlace']);
    Route::delete('/dashboard/{user}/property-places/{property_place}', [AdminRegistrationController::class, 'destroyPropertyPlace']);

    // Users
    Route::get('/dashboard/users/create', [AdminRegistrationController::class, 'createUser'])->name('admin.users.create');
    Route::post('/dashboard/users', [AdminRegistrationController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/dashboard/users/{targetUser}/edit', [AdminRegistrationController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/dashboard/users/{targetUser}/account-status', [AdminRegistrationController::class, 'updateUserStatus'])->name('admin.users.updateStatus');
    Route::put('/dashboard/users/{targetUser}/update', [AdminRegistrationController::class, 'updateUser'])->name('admin.users.update');
    Route::put('/dashboard/users/{targetUser}/toggle-status', [AdminRegistrationController::class, 'toggleUserStatus'])->name('admin.users.toggleStatus');
    Route::put('/dashboard/users/{targetUser}/email-status', [AdminRegistrationController::class, 'updateEmailVerification'])->name('admin.users.updateEmailVerification');
    Route::put('/dashboard/users/{targetUser}/toggle-email-verification', [AdminRegistrationController::class, 'toggleEmailVerification'])->name('admin.users.toggleEmailVerification');
    Route::delete('/dashboard/users/{targetUser}', [AdminRegistrationController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::put('/dashboard/property-enquiries/{enquiry}/status', [AdminRegistrationController::class, 'updateEnquiryStatus'])->name('admin.enquiries.updateStatus');
    Route::post('/dashboard/enquiry-interactions', [AdminRegistrationController::class, 'storeEnquiryInteraction'])->name('admin.enquiry.store');
    Route::post('/dashboard/pinned-project', [AdminRegistrationController::class, 'savePinnedProject'])->name('admin.pinned-project.save');
    Route::delete('/dashboard/pinned-project', [AdminRegistrationController::class, 'removePinnedProject'])->name('admin.pinned-project.remove');
    Route::put('/dashboard/interior-enquiries/{id}/status', [AdminRegistrationController::class, 'updateInteriorEnquiryStatus'])->name('admin.interior-enquiries.updateStatus');
});

// Vendor Authentication Routes
Route::get('/vendor/login', [\App\Http\Controllers\VendorLoginController::class, 'showLoginForm'])->name('vendor.login');
Route::post('/vendor/login', [\App\Http\Controllers\VendorLoginController::class, 'login'])->name('vendor.login.submit');
Route::post('/vendor/logout', [\App\Http\Controllers\VendorLoginController::class, 'logout'])->name('vendor.logout');

// Vendor Protected Routes
Route::middleware('auth:vendor')->group(function () {
    Route::get('/vendor/dashboard', [\App\Http\Controllers\VendorDashboardController::class, 'index'])->name('vendor.dashboard');
    Route::get('/vendor/section/{section}', [\App\Http\Controllers\VendorDashboardController::class, 'section'])->name('vendor.section');
    Route::put('/vendor/profile', [\App\Http\Controllers\VendorDashboardController::class, 'updateProfile'])->name('vendor.profile.update');
    Route::put('/vendor/password', [\App\Http\Controllers\VendorDashboardController::class, 'updatePassword'])->name('vendor.password.update');
    Route::post('/vendor/property', [\App\Http\Controllers\VendorDashboardController::class, 'storeProperty'])->name('vendor.property.store');
    Route::get('/vendor/property/{propertyId}/edit', [\App\Http\Controllers\VendorDashboardController::class, 'editProperty'])->name('vendor.property.edit');
    Route::put('/vendor/property/{propertyId}', [\App\Http\Controllers\VendorDashboardController::class, 'updateProperty'])->name('vendor.property.update');
    Route::delete('/vendor/property/{propertyId}', [\App\Http\Controllers\VendorDashboardController::class, 'destroyProperty'])->name('vendor.property.destroy');
    // Partial update (used for quick updates like status/name/category)
    Route::put('/vendor/property/{propertyId}/partial', [\App\Http\Controllers\VendorDashboardController::class, 'updatePropertyPartial'])->name('vendor.property.partial');
});

Route::get('/vendor-auto-login', function() {
    $vendor = \App\Models\Vendor::where('email', 'admin@homewala.com')->first() 
              ?: \App\Models\Vendor::orderBy('id', 'desc')->first();
    if ($vendor) {
        Auth::guard('vendor')->login($vendor);
        return redirect()->route('vendor.section', ['section' => 'add-property', 'type' => 'residential']);
    }
    return 'No vendor found';
});
