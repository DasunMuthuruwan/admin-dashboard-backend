<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('register',[AuthController::class,'register'])->name('api.register');
Route::post('login',[AuthController::class,'login'])->name('api.login');

Route::group(['middleware' => 'auth:api'],function(){

    Route::get('profile',[UserController::class,'profile']);
    Route::put('users/info',[UserController::class,'updateInfo']);
    Route::put('users/password',[UserController::class,'updatePassword']);
    Route::post('upload',[ImageController::class,'upload']);
    Route::get('export_csv',[OrderController::class,'export']);
    Route::get('chart',[DashboardController::class,'chart']);

    Route::apiResource('/users',UserController::class);

    // User role routes
    Route::apiResource('roles',RoleController::class);

    // Product routes
    Route::apiResource('products',ProductController::class);

    // Order routes
    Route::apiResource('orders',OrderController::class)->only('index','show');

    // Permission routes
    Route::apiResource('permissions',PermissionController::class)->only('index');

});

// Route::get('users/{id}',[UserController::class,'show']);
// Route::post('users',[UserController::class,'create']);
// Route::put('users/{id}',[UserController::class,'update']);
// Route::delete('users/{id}',[UserController::class,'destroy']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
