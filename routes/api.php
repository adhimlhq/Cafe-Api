<?php

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="Cafe Management API",
 *         version="1.0.0",
 *         description="API documentation for the Cafe Management system"
 *     )
 * )
 */
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CafeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
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

Route::post('/login', [UserController::class, 'login'])->middleware('throttle:10,1'); //Oke

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware(['role:superadmin,owner', 'throttle:60,1'])->group(function () {
        //Manage User
        Route::get('/users', [UserController::class, 'index']); // Oke || View
        Route::post('/storeUsers', [UserController::class, 'storeUser']); // Oke || Store
        Route::get('/users/{id}', [UserController::class, 'detailUser']); // Oke || Detail
        Route::put('/updateUsers/{user}', [UserController::class, 'updateUser']); // Oke || update
        Route::delete('/deleteUsers/{user}', [UserController::class, 'destroy']); // Oke || Delete

        //Manage Cafe
        Route::get('/allCafes', [CafeController::class, 'index']); // Oke || View
        Route::post('/storeCafes', [CafeController::class, 'store']); // Oke || Store
        Route::delete('/deleteCafes/{cafe}', [CafeController::class, 'destroy']); // Oke || delete

        //Manage manager by Owner
        Route::get('/detailCafes/{cafe}', [CafeController::class, 'show']); //Oke || detail
        Route::put('/updateCafes/{cafe}', [CafeController::class, 'update']); // Oke || update
    });

    // Routes accessible by Superadmin, Owner, and Manager
    Route::middleware(['role:superadmin,owner,manager', 'throttle:60,1'])->group(function () {
        Route::get('/menu', [MenuController::class, 'index']); // Oke || index

        Route::middleware('role:manager')->group(function () {
            Route::get('/cafes/indexMenu', [MenuController::class, 'indexMenu']); // Oke || index
            Route::post('/cafes/storeMenus', [MenuController::class, 'store']); // Oke || Store

            Route::get('/cafes/{cafe}/menus/{menu}/detail', [MenuController::class, 'detailMenu']); // Oke || Detail Menu
            Route::put('/cafes/{cafe}/menus/{menu}/update', [MenuController::class, 'update']); // Ok || Update
            Route::delete('/cafes/{cafe}/menus/{menu}/delete', [MenuController::class, 'destroy']); // oke || delete
        });
    });
});
