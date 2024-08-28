<?php

use App\Http\Controllers\StaffController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/register',[RegisterController::class,'index'])->name('register.index');
Route::post('/registerdata',[RegisterController::class,'store'])->name('registerdata.store');
Route::post('/login',[LoginController::class,'store'])->name('login.store');

Route::group(['middleware' => ['ValidUser']], function () {

    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard.index');
    Route::get('/logout',[LoginController::class,'logout'])->name('logout');
    Route::get('/catagoryindex',[CategoryController::class,'index'])->name('category.index');
    Route::get('/createcategory',[CategoryController::class,'create'])->name('category.create');
    Route::post('/storecategory',[CategoryController::class,'store'])->name('category.store');
    Route::get('viewcategory/{id}',[CategoryController::class,'show'])->name('category.show');
    Route::get('/editviewcategory/{id}',[CategoryController::class,'edit'])->name('category.edit');
    Route::post('/storeeditcategory/{id}',[CategoryController::class,'update'])->name('category.update');
    Route::get('category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
    Route::get('/trashcategory/view',[CategoryController::class,'trashcategory'])->name('trashcategory.view');
    Route::get('/trashcategory/restore/{id}',[CategoryController::class,'restore'])->name('restore.category');
    Route::get('/trashcategory/forcedelete/{id}',[CategoryController::class,'forcedelete'])->name('forcedelete.category');
    Route::get('menuindex',[MenuController::class,'index'])->name('menu.index');
    Route::get('/menucreate',[MenuController::class,'create'])->name('menu.create');
    Route::post('/menustore',[MenuController::class,'store'])->name('menu.store');
    Route::get('/menuview/{id}',[MenuController::class,'show'])->name('menu.show');
    Route::get('/menuedit/{id}',[MenuController::class,'edit'])->name('menu.edit');
    Route::post('/menuupdate/{id}',[MenuController::class,'update'])->name('menu.update');
    Route::get('menu/{id}',[MenuController::class,'destroy'])->name('menu.destroy');
    Route::get('/trashmenu/view',[MenuController::class,'trashmenu'])->name('trashmenu.view');
    Route::get('/trashmenu/restore/{id}',[MenuController::class,'restore'])->name('restore.menu');
    Route::get('/trashmenu/forcedelete/{id}',[MenuController::class,'forcedelete'])->name('forcedelete.menu');
    Route::get('staffindex',[StaffController::class,'index'])->name('staff.index');
    Route::get('staffcreate',[StaffController::class,'create'])->name('staff.create');
    Route::post('staffstore',[StaffController::class,'store'])->name('staff.store');
    Route::get('staffview/{id}',[StaffController::class,'show'])->name('staff.show');
    Route::get('staffedit/{id}',[StaffController::class,'edit'])->name('staff.edit');
    Route::post('staffupdate/{id}',[StaffController::class,'update'])->name('staff.update');
    Route::get('staff/{id}',[StaffController::class,'destroy'])->name('staff.destroy');
    Route::get('trashstaff/view',[StaffController::class,'trashstaff'])->name('trashstaff.view');
    Route::get('trashstaff/restore/{id}',[StaffController::class,'restore'])->name('restore.staff');
    Route::get('trashstaff/forcedelete/{id}',[StaffController::class,'forcedelete'])->name('forcedelete.staff');
    Route::get('tableindex',[TableController::class,'index'])->name('table.index');
    Route::get('tablecreate',[TableController::class,'create'])->name('table.create');
    Route::post('tablestore',[TableController::class,'store'])->name('table.store');
    Route::get('tableview/{id}',[TableController::class,'show'])->name('table.show');
    Route::get('tableedit/{id}',[TableController::class,'edit'])->name('table.edit');
    Route::post('tableupdate/{id}',[TableController::class,'update'])->name('table.update');
    Route::get('table/{id}',[TableController::class,'destroy'])->name('table.destroy');
    Route::get('trashtable/view',[TableController::class,'trashtable'])->name('trashtable.view');
    Route::get('trashtable/restore/{id}',[TableController::class,'restore'])->name('restore.table');
    Route::get('trashtable/forcedelete/{id}',[TableController::class,'forcedelete'])->name('forcedelete.table');
    Route::get('/profileedit', [RegisterController::class, 'edit'])->name('profile.edit');
    Route::post('/profileupdate', [RegisterController::class, 'update'])->name('profile.update');


});
    
