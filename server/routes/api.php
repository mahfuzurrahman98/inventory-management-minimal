<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\InventoryController;

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

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    // inventory routes
    Route::resource('/inventories', InventoryController::class);

    // item routes
    Route::resource('/items', ItemController::class);
});

// file stream route
// Route::get('/storage/{filename}', function ($filename) {
//     $path = public_path('/images/' . $filename);
//     // dd($path);
//     if (!File::exists($path)) {
//         abort(404);
//     }

//     $file = File::get($path);
//     $type = File::mimeType($path);

//     $response = Response::make($file, 200);
//     $response->header('Content-Type', $type);

//     return $response;
// });
