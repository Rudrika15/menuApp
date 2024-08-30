<?php

use App\Http\Controllers\API\CategoryController;
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
    Route::put('/table/edit/{id?}', [TableController::class, 'editTable']);
    Route::delete('/table/delete/{id?}', [TableController::class, 'deleteTable']);
    // trash
    Route::get('/trashTable', [TableController::class, 'getTrashTable']);
    Route::put('/table/restore/{id?}', [TableController::class, 'restoreDeletedTable']);
    Route::delete('/table/delete/{id?}', [TableController::class, 'permanentDeleteTable']);


    // staff routes
    Route::get('/staff', [MemberController::class, 'getStaffs']);
    Route::get('/trashStaff', [MemberController::class, 'getTrashStaffs']);
    Route::post('/staff', [MemberController::class, 'addStaffs']);
    Route::put('/staff/{id?}', [MemberController::class, 'editStaff']);
    Route::delete('/staff/{id?}', [MemberController::class, 'deleteStaff']);
    // trash 
    Route::get('/trashStaff', [MemberController::class, 'getTrashStaff']);
    Route::put('/staff/restore/{id?}', [MemberController::class, 'restoreDeletedStaff']);
    Route::delete('/staff/delete/{id?}', [MemberController::class, 'permanentDeleteStaff']);


    // Category api
    Route::get('/categories', [CategoryController::class, 'getCategories']);
    Route::post('/categories', [CategoryController::class, 'addCategories']);
    Route::put('/category/{id?}', [CategoryController::class, 'editCategories']);
    Route::delete('/category/{id?}', [CategoryController::class, 'deleteCategories']);
    // trash
    Route::get('/trashCategories', [CategoryController::class, 'getTrashCategories']);
    Route::put('/category/restore/{id?}', [CategoryController::class, 'restoreDeletedCategory']);
    Route::delete('/category/delete/{id?}', [CategoryController::class, 'permanentDeleteCategory']);

    // menu api
    Route::get('/menu', [MenuController::class, 'getMenus']);
    // Route::post('/menu', [MenuController::class, 'addMenu']);
    // Route::put('/menu/edit/{id?}', [MenuController::class, 'editMenu']);
    // Route::delete('/menu/delete/{id?}', [MenuController::class, 'deleteMenu']);
    // trash
    Route::get('/trashMenu', [MenuController::class, 'getTrashMenus']);
});

Route::get('/pass', [CategoryController::class, 'addPassword']);
