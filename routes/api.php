<?php 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TenantController;

Route::post('/tenantlogin', [AuthController::class, 'tenantLogin']);

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/admin/approve/{id}', [AdminController::class, 'approveUser']);

// Define API rate limiter (60 requests per minute)
RateLimiter::for('api', function (Request $request) {
    return \Illuminate\Cache\RateLimiting\Limit::perMinute(60)->by(
        optional($request->user())->id ?: $request->ip()
    );
});


Route::middleware(['auth:sanctum', 'tenant'])->group(function () {

	Route::get('/dashboard', function () {
        return "Welcome to your tenant dashboard!";
    });
    Route::get('/products', [TenantController::class, 'listProducts']);
    Route::post('/createproduct', [TenantController::class, 'createProduct']);
    Route::post('/orders', [TenantController::class, 'placeOrder']);
});
