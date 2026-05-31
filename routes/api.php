<?php
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProviderController;
use App\Http\Controllers\API\SearchController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\ProviderProfileController;
use App\Http\Controllers\API\CustomerProfileController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\RatingController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProviderController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminAdController;
use App\Http\Controllers\Admin\AdminReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/home', [HomeController::class, 'index']);

Route::prefix('auth')->group(function () {
    Route::get('/main-services', [AuthController::class, 'mainServices']);
    Route::post('/register/user', [AuthController::class, 'registerUser']);
    Route::post('/register/provider', [AuthController::class, 'registerProvider']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
    Route::middleware('auth:sanctum')->post('/delete-account-request', [AuthController::class, 'deleteAccountRequest']);
});


Route::prefix('provider')->middleware('auth:sanctum')->group(function () {
    Route::get('/sub-services', [ProviderController::class, 'subServices']);
    Route::post('/complete-profile', [ProviderController::class, 'completeProfile']);
});



Route::prefix('chat')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/button-status/{targetUserId}', [ChatController::class, 'checkChatButtonVisibility']);
    Route::post('/start/{targetUserId}', [ChatController::class, 'startPrivateChat']);
    Route::get('/list', [ChatController::class, 'chatList']);
    Route::get('/{chatId}/messages', [ChatController::class, 'getMessages']);
    Route::post('/{chatId}/send', [ChatController::class, 'sendMessage']);
});

// ==================== Search Routes ====================
Route::prefix('search')->middleware(['auth:sanctum'])->group(function () {
   
    Route::get('/sub-services/{main_service_id}', [SearchController::class, 'getSubServices']);
    
 
    Route::get('/top-providers/{main_service_id}', [SearchController::class, 'getTopProviders']);
    

    Route::get('/providers', [SearchController::class, 'searchProviders']);
});

// ==================== Ratings Routes ====================
Route::prefix('ratings')->middleware(['auth:sanctum'])->group(function () {
    
   
    Route::post('/', [RatingController::class, 'store']);
    
    
    Route::put('/{id}', [RatingController::class, 'update']);
    
  
    Route::delete('/{id}', [RatingController::class, 'destroy']);
    
   
    Route::get('/{id}', [RatingController::class, 'show']);
    
 
   Route::get('/user/my_ratings', [RatingController::class, 'myRatings']);
    
 
    Route::get('/provider/{provider_id}', [RatingController::class, 'getProviderRatings']);
    
   
    Route::get('/provider/{provider_id}/average', [RatingController::class, 'getProviderAverage']);
});

// ==================== Provider Profile Routes ====================
Route::prefix('provider')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/myprofile', [ProviderProfileController::class, 'show']);
    Route::put('/profile', [ProviderProfileController::class, 'update']);
    Route::post('/profile/avatar', [ProviderProfileController::class, 'updateAvatar']);
    Route::post('/profile/certificates', [ProviderProfileController::class, 'manageCertificates']);
    Route::post('/gallery', [ProviderProfileController::class, 'manageGallery']);
});

// ==================== Customer Profile Routes ====================
Route::prefix('customer')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile', [CustomerProfileController::class, 'show']);
    Route::put('/profile', [CustomerProfileController::class, 'update']);
    Route::post('/profile/avatar', [CustomerProfileController::class, 'updateAvatar']);
});

// ==================== Cross-Profile Routes ====================
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile/{user_id}', [ProfileController::class, 'show']);
    Route::post('/provider/{provider_id}/rate', [ProfileController::class, 'rateProvider']);
    Route::post('/provider/{provider_id}/complaints', [ProfileController::class, 'complaintProvider']);
    Route::post('/provider/{provider_id}/location', [ProfileController::class, 'getProviderLocation']);
    Route::post('/customer/favourites/{provider_id}', [ProfileController::class, 'toggleFavourite']);
    Route::post('/ratings/{rating_id}/report', [ProfileController::class, 'reportComment']);
});

// ==================== Admin Routes ====================
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout']);

        // Dashboard
        Route::get('/dashboard/statistics', [AdminDashboardController::class, 'statistics']);
        Route::get('/dashboard/charts', [AdminDashboardController::class, 'charts']);
        Route::get('/dashboard/top-searches', [AdminDashboardController::class, 'topSearchedServices']);

        // Registration Requests
        Route::get('/registrations/pending', [AdminProviderController::class, 'pendingRegistrations']);
        Route::get('/registrations/{providerId}', [AdminProviderController::class, 'registrationDetails']);
        Route::post('/registrations/{providerId}/approve', [AdminProviderController::class, 'approveRegistration']);
        Route::post('/registrations/{providerId}/reject', [AdminProviderController::class, 'rejectRegistration']);

        // Provider Management
        Route::get('/providers', [AdminProviderController::class, 'index']);
        Route::get('/providers/{providerId}', [AdminProviderController::class, 'show']);
        Route::post('/providers/{providerId}/ban', [AdminProviderController::class, 'ban']);
        Route::post('/providers/{providerId}/unban', [AdminProviderController::class, 'unban']);
        Route::delete('/providers/{providerId}', [AdminProviderController::class, 'delete']);
        Route::put('/providers/{providerId}', [AdminProviderController::class, 'update']);

        // Customer Management
        Route::get('/customers', [AdminCustomerController::class, 'index']);
        Route::get('/customers/{userId}', [AdminCustomerController::class, 'show']);
        Route::post('/customers/{userId}/ban', [AdminCustomerController::class, 'ban']);
        Route::post('/customers/{userId}/unban', [AdminCustomerController::class, 'unban']);
        Route::delete('/customers/{userId}', [AdminCustomerController::class, 'delete']);
        Route::put('/customers/{userId}', [AdminCustomerController::class, 'update']);

        // Services & Sub-Services Management
        Route::get('/services', [AdminServiceController::class, 'index']);
        Route::post('/services', [AdminServiceController::class, 'store']);
        Route::put('/services/{serviceId}', [AdminServiceController::class, 'update']);
        Route::delete('/services/{serviceId}', [AdminServiceController::class, 'destroy']);
        Route::post('/services/{serviceId}/sub-services', [AdminServiceController::class, 'storeSubService']);
        Route::put('/sub-services/{subServiceId}', [AdminServiceController::class, 'updateSubService']);
        Route::delete('/sub-services/{subServiceId}', [AdminServiceController::class, 'destroySubService']);

        // Ads Management
        Route::get('/ads', [AdminAdController::class, 'index']);
        Route::post('/ads', [AdminAdController::class, 'store']);
        Route::delete('/ads/{adId}', [AdminAdController::class, 'destroy']);

        // Reports Management — بلاغات المزودين (comment reports)
        Route::get('/reports/comments', [AdminReportController::class, 'commentReports']);
        Route::post('/reports/comments/{reportId}/ban', [AdminReportController::class, 'banCommentAuthor']);
        Route::delete('/reports/comments/{reportId}', [AdminReportController::class, 'dismissCommentReport']);

        // Reports Management — بلاغات الزبائن (customer complaints)
        Route::get('/reports/complaints', [AdminReportController::class, 'complaints']);
        Route::post('/reports/complaints/{complaintId}/ban', [AdminReportController::class, 'banComplaintProvider']);
        Route::delete('/reports/complaints/{complaintId}', [AdminReportController::class, 'dismissComplaint']);
    });
});