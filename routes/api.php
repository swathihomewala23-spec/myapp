<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\InteriorController;
use App\Http\Controllers\Api\InteriorEnquiryController;
use App\Http\Controllers\Api\PartnerController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\WebsiteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/interior-enquiries', [InteriorEnquiryController::class, 'store']);
Route::post('/property-enquiries', [InteriorEnquiryController::class, 'storePropertyEnquiry']);
Route::get('/banner-images', [BannerController::class, 'index']);
Route::get('/website-info', [WebsiteController::class, 'info']);
Route::get('/get-our-partners', [PartnerController::class, 'index']);
Route::post('/interior/home', [InteriorController::class, 'home']);
Route::post('/get-filtered-listview-properties', [PropertyController::class, 'filter']);
Route::get('/get-property-details/{id}', [PropertyController::class, 'show']);

Route::get('/buyer/wishlist', function () {
    return response()->json(['success' => true, 'data' => []]);
});

Route::post('/buyer/wishlist', function () {
    return response()->json(['success' => true, 'message' => 'Wishlist updated']);
});

Route::get('/buyer/profile', function () {
    return response()->json(['success' => true, 'data' => []]);
});

Route::post('/buyer/update-profile', function () {
    return response()->json(['success' => true, 'message' => 'Profile updated']);
});

Route::post('/buyer/change-password', function () {
    return response()->json(['success' => true, 'message' => 'Password changed']);
});

Route::post('/register-user', function () {
    return response()->json(['success' => true, 'message' => 'User registered']);
});

Route::post('/register-vendor', function () {
    return response()->json(['success' => true, 'message' => 'Vendor registered']);
});

Route::post('/login', function () {
    return response()->json(['success' => true, 'message' => 'Logged in']);
});

Route::post('/logout', function () {
    return response()->json(['success' => true, 'message' => 'Logged out']);
});

Route::post('/forgot-password', function () {
    return response()->json(['success' => true, 'message' => 'Forgot password request received']);
});

Route::post('/reset-password', function () {
    return response()->json(['success' => true, 'message' => 'Password reset']);
});

Route::post('/verify-otp', function () {
    return response()->json(['success' => true, 'message' => 'OTP verified']);
});

Route::post('/resend-otp', function () {
    return response()->json(['success' => true, 'message' => 'OTP resent']);
});

Route::post('/get-filtered-listview-properties', function () {
    return response()->json(['success' => true, 'data' => []]);
});

Route::get('/get-property-details/{id}', function ($id) {
    return response()->json(['success' => true, 'data' => ['id' => $id]]);
});

Route::get('/get-footer-links', function () {
    return response()->json(['success' => true, 'data' => []]);
});

Route::get('/get-property-places-count', function () {
    return response()->json(['success' => true, 'data' => []]);
});

Route::get('/new-launched-properties', function () {
    return response()->json(['success' => true, 'data' => []]);
});

Route::get('/elite-properties', function () {
    return response()->json(['success' => true, 'data' => []]);
});

Route::get('/why-choose-us', function () {
    return response()->json(['success' => true, 'data' => []]);
});