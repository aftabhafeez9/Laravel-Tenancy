<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/admin/approve/{id}', [AdminController::class, 'approveUser']);

Route::middleware(['auth', 'tenant'])->group(function () {
    Route::get('/dashboard', function () {
        return "Welcome to your tenant dashboard!";
    });
});



// Route::get('/', function () {
//     return view('welcome');
// });
