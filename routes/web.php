<?php

use App\Http\Controllers\AdminRegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdminRegistrationController::class, 'create'])->name('admin.register');
Route::post('/', [AdminRegistrationController::class, 'store'])->name('admin.register.store');
Route::get('/dashboard/{user}', [AdminRegistrationController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/dashboard/{user}/properties/create', [AdminRegistrationController::class, 'choosePropertyType'])->name('admin.properties.create');
Route::get('/dashboard/{user}/properties/create/{type}', [AdminRegistrationController::class, 'createPropertyForm'])->name('admin.properties.create.type');
Route::get('/dashboard/{user}/section/{section}', [AdminRegistrationController::class, 'section'])->name('admin.section');
Route::post('/dashboard/{user}/amenities', [AdminRegistrationController::class, 'storeAmenity'])->name('admin.amenities.store');
Route::put('/dashboard/{user}/amenities/{amenity}', [AdminRegistrationController::class, 'updateAmenity'])->name('admin.amenities.update');
Route::delete('/dashboard/{user}/amenities/{amenity}', [AdminRegistrationController::class, 'destroyAmenity'])->name('admin.amenities.destroy');
Route::post('/dashboard/{user}/categories', [AdminRegistrationController::class, 'storeCategory'])->name('admin.categories.store');
Route::put('/dashboard/{user}/categories/{category}', [AdminRegistrationController::class, 'updateCategory'])->name('admin.categories.update');
Route::delete('/dashboard/{user}/categories/{category}', [AdminRegistrationController::class, 'destroyCategory'])->name('admin.categories.destroy');

Route::post('/dashboard/{user}/countries', [AdminRegistrationController::class, 'storeCountry'])->name('admin.countries.store');
Route::put('/dashboard/{user}/countries/{country}', [AdminRegistrationController::class, 'updateCountry'])->name('admin.countries.update');
Route::delete('/dashboard/{user}/countries/{country}', [AdminRegistrationController::class, 'destroyCountry'])->name('admin.countries.destroy');

Route::post('/dashboard/{user}/states', [AdminRegistrationController::class, 'storeState'])->name('admin.states.store');
Route::put('/dashboard/{user}/states/{state}', [AdminRegistrationController::class, 'updateState'])->name('admin.states.update');
Route::delete('/dashboard/{user}/states/{state}', [AdminRegistrationController::class, 'destroyState'])->name('admin.states.destroy');

Route::post('/dashboard/{user}/cities', [AdminRegistrationController::class, 'storeCity'])->name('admin.cities.store');
Route::put('/dashboard/{user}/cities/{city}', [AdminRegistrationController::class, 'updateCity'])->name('admin.cities.update');
Route::delete('/dashboard/{user}/cities/{city}', [AdminRegistrationController::class, 'destroyCity'])->name('admin.cities.destroy');

Route::post('/dashboard/{user}/property-places', [AdminRegistrationController::class, 'storePropertyPlace'])->name('admin.property-places.store');
Route::get('/dashboard/{user}/property-places/{property_place}/edit', [AdminRegistrationController::class, 'editPropertyPlace'])->name('admin.property-places.edit');
Route::put('/dashboard/{user}/property-places/{property_place}', [AdminRegistrationController::class, 'updatePropertyPlace'])->name('admin.property-places.update');
Route::delete('/dashboard/{user}/property-places/{property_place}', [AdminRegistrationController::class, 'destroyPropertyPlace'])->name('admin.property-places.destroy');

Route::get('/dashboard/{user}/users/create', [AdminRegistrationController::class, 'createUser'])->name('admin.users.create');
Route::post('/dashboard/{user}/users', [AdminRegistrationController::class, 'storeUser'])->name('admin.users.store');
Route::get('/dashboard/{user}/users/{targetUser}/edit', [AdminRegistrationController::class, 'editUser'])->name('admin.users.edit');
Route::put('/dashboard/{user}/users/{targetUser}/account-status', [AdminRegistrationController::class, 'updateUserStatus'])->name('admin.users.updateStatus');
Route::put('/dashboard/{user}/users/{targetUser}/update', [AdminRegistrationController::class, 'updateUser'])->name('admin.users.update');
Route::put('/dashboard/{user}/users/{targetUser}/toggle-status', [AdminRegistrationController::class, 'toggleUserStatus'])->name('admin.users.toggleStatus');
Route::put('/dashboard/{user}/users/{targetUser}/email-status', [AdminRegistrationController::class, 'updateEmailVerification'])->name('admin.users.updateEmailVerification');
Route::put('/dashboard/{user}/users/{targetUser}/toggle-email-verification', [AdminRegistrationController::class, 'toggleEmailVerification'])->name('admin.users.toggleEmailVerification');
Route::delete('/dashboard/{user}/users/{targetUser}', [AdminRegistrationController::class, 'destroyUser'])->name('admin.users.destroy');
