<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/registration_buyer', [AuthController::class, 'registrationBuyer'])->name('registrationBuyer');
Route::post('/registration_seller', [AuthController::class, 'registrationSeller'])->name('registrationSeller');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function() {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::prefix('products')->group(function () {
        Route::get('/view', [ProductController::class, 'viewProduct'])->name('viewProduct');
        Route::middleware(['sellerMiddleware'])->group(function() {
            Route::post('/add', [ProductController::class, 'addProduct'])->name('addProduct');
            Route::post('/delete', [ProductController::class, 'deleteProduct'])->name('deleteProduct');
        });
    });
});