<?php

use App\Http\Controllers\API\MemberController;
use App\Http\Controllers\API\RestaurantController;
use App\Http\Controllers\API\TableController;
use App\Http\Controllers\API\StaffController;
use App\Http\Controllers\API\MenuController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Restaurant
Route::post('/login', [RestaurantController::class, 'login']);
Route::post('/staffLogin', [MemberController::class, 'staffLogin']);
Route::post('/restaurant/registration', [RestaurantController::class, 'restaurantRegistration']);

Route::get('/restaurant', [RestaurantController::class, 'getRestaurants']);
Route::get('/restaurant/{id?}', [RestaurantController::class, 'getRestaurantById']);

Route::middleware('auth.member')->group(function () {
    Route::get('/tableList', [TableController::class, 'tableList']);
    Route::get('/menuList', [MenuController::class, 'menuList']);
});
Route::middleware('auth.restaurant')->group(function () {

    //table routes
    Route::get('/table', [TableController::class, 'getTables']);
    Route::post('/table', [TableController::class, 'addTables']);

    // staff routes
    Route::get('/staff', [MemberController::class, 'getStaffs']);
    Route::post('/staff', [MemberController::class, 'addStaffs']);
    
    // menu api
 

});