<?php

use Illuminate\Support\Facades\Route;

//Auth
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\PermissionController;
use App\Http\Controllers\Auth\ApprovalController;
use App\Models\Auth\Role;
use Illuminate\Support\Facades\Auth;

//Master 
//Structur
use App\Http\Controllers\Master\Structur\MainCorporateController;
use App\Http\Controllers\Master\Structur\SubCorporateController;
use App\Http\Controllers\Master\Structur\BagianController;
use App\Http\Controllers\Master\Structur\SubBagianController;
use App\Http\Controllers\Master\Structur\SubSubBagianController;
use App\Models\Auth\Approval;
use App\Models\Auth\Permission;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// user
Route::prefix('admin/auth')->name('admin.auth.')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::get('/index', [AuthController::class, 'index'])->name('index');
    Route::get('/show', [AuthController::class, 'show'])->name('show');
    Route::get('/create', [AuthController::class, 'create'])->name('create');
    Route::post('/store', [AuthController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [AuthController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [AuthController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [AuthController::class, 'delete'])->name('delete');

    Route::post('profile-update/{id}', [AuthController::class, 'profileUpdate'])->name('profile.update');
    Route::post('password-update/{id}', [AuthController::class, 'passwordUpdate'])->name('password.update');
});

// role 
Route::prefix('admin/setting-role')->name('admin.setting.role.')->group(function () {
    Route::get('/data', [RoleController::class, 'getData'])->name('data');
    Route::get('/index', [RoleController::class, 'index'])->name('index');
    Route::get('/show/{id}', [RoleController::class, 'show'])->name('show');
    Route::get('/track/{id}', [RoleController::class, 'track'])->name('track');
    // Route::post('/create', [RoleController::class, 'create'])->name('create');
    Route::post('/store', [RoleController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('edit');
    Route::get('/assign-permission/{id}', [RoleController::class, 'assignPermission'])->name('assignPermission');
    Route::post('/store-permission/{id}', [RoleController::class, 'storePermission'])->name('storePermission');
    Route::put('/update/{id}', [RoleController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [RoleController::class, 'destroy'])->name('delete');
});

// permission
Route::prefix('admin/setting-permission')->name('admin.setting.permission.')->group(function () {
    Route::get('/data', [PermissionController::class, 'getData'])->name('data');
    Route::get('/index', [PermissionController::class, 'index'])->name('index');
    Route::get('/show/{id}', [PermissionController::class, 'show'])->name('show');
    Route::post('/create', [PermissionController::class, 'create'])->name('create');
    Route::post('/store', [PermissionController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [PermissionController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [PermissionController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [PermissionController::class, 'destroy'])->name('delete');
});

// approval
Route::prefix('admin/setting-approval')->name('admin.setting.approval.')->group(function () {
    Route::get('/index', [ApprovalController::class, 'index'])->name('index');
    Route::get('/show/{id}', [ApprovalController::class, 'show'])->name('show');
    Route::get('/create', [ApprovalController::class, 'create'])->name('create');
    Route::post('/store', [ApprovalController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [ApprovalController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [ApprovalController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [ApprovalController::class, 'destroy'])->name('delete');
});

//view log
Route::get('admin/setting-log/index',[AuthController::class, 'viewLog'])->name('admin.setting.log.index');

// Master
    //Structur
    Route::prefix('admin/structure-main-corp')->name('admin.structure.mainCorp.')->group(function () {
        Route::get('/index', [MainCorporateController::class, 'index'])->name('index');
        Route::get('/show/{id}', [MainCorporateController::class, 'show'])->name('show');
        Route::get('/create', [MainCorporateController::class, 'create'])->name('create');
        Route::post('/store', [MainCorporateController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MainCorporateController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [MainCorporateController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [MainCorporateController::class, 'destroy'])->name('delete');
    });

    Route::prefix('admin/structure-sub-corp')->name('admin.structure.subCorp.')->group(function () {
        Route::get('/index', [SubCorporateController::class, 'index'])->name('index');
        Route::get('/show/{id}', [SubCorporateController::class, 'show'])->name('show');
        Route::get('/create', [SubCorporateController::class, 'create'])->name('create');
        Route::post('/store', [SubCorporateController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [SubCorporateController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [SubCorporateController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [SubCorporateController::class, 'destroy'])->name('delete');
    });

    Route::prefix('admin/structure-bagian')->name('admin.structure.bagian.')->group(function () {
        Route::get('/index', [BagianController::class, 'index'])->name('index');
        Route::get('/show/{id}', [BagianController::class, 'show'])->name('show');
        Route::get('/create', [BagianController::class, 'create'])->name('create');
        Route::post('/store', [BagianController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BagianController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [BagianController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [BagianController::class, 'destroy'])->name('delete');
    });

    Route::prefix('admin/structure-sub-bagian')->name('admin.structure.subBagian.')->group(function () {
        Route::get('/index', [SubBagianController::class, 'index'])->name('index');
        Route::get('/show/{id}', [SubBagianController::class, 'show'])->name('show');
        Route::get('/create', [SubBagianController::class, 'create'])->name('create');
        Route::post('/store', [SubBagianController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [SubBagianController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [SubBagianController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [SubBagianController::class, 'destroy'])->name('delete');
    });

    Route::prefix('admin/structure-sub-sub-bagian')->name('admin.structure.subSubBagian.')->group(function () {
        Route::get('/index', [SubSubBagianController::class, 'index'])->name('index');
        Route::get('/show/{id}', [SubSubBagianController::class, 'show'])->name('show');
        Route::get('/create', [SubSubBagianController::class, 'create'])->name('create');
        Route::post('/store', [SubSubBagianController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [SubSubBagianController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [SubSubBagianController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [SubSubBagianController::class, 'destroy'])->name('delete');
    });

// ajax
Route::get('/ajax/search-sub-bagian', [SubBagianController::class, 'searchSubBagian']);



Route::get('/home', function () {
    return view('Template.table');
})->name('dash.home');
