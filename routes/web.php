<?php

use Illuminate\Support\Facades\Route;

//Auth
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\PermissionController;
use App\Http\Controllers\Auth\ApprovalController;
use App\Http\Controllers\Auth\LogActivityController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\Master\Blog\BlogController;
use App\Models\Auth\Role;
use Illuminate\Support\Facades\Auth;

//Master 
//Structur
use App\Http\Controllers\Master\Structur\MainCorporateController;
use App\Http\Controllers\Master\Structur\DireksiController;
use App\Http\Controllers\Master\Structur\BagianController;
use App\Http\Controllers\Master\Position\PositionController;
use App\Http\Controllers\Master\Structur\SubBagianController;
use App\Http\Controllers\Master\Structur\SubSubBagianController;

// Approval
use App\Http\Controllers\Approval\ApprovalFlowController;
use App\Http\Controllers\Approval\ApprovalFlowStepsController;
use App\Http\Controllers\Approval\ApprovalFlowStepsApproverController;

//Perencanaan 
use App\Http\Controllers\Perencanaan\PerencanaanController;

// Auth
use App\Models\Auth\Permission;
use App\Models\Master\Position;
use GuzzleHttp\Middleware;

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
    return view('Auth.login');
})->name('login');

Route::post('/login-process', [AuthController::class, 'login'])->name('auth.login');
Route::post('/check-email-login', [AuthController::class, 'checkEmailLogin'])->name('auth.check.login');


Route::group(['middleware' => 'custom.auth'], function () {
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
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('get-data-user', [AuthController::class, 'user'])->name('user');
        Route::post('profile-update', [AuthController::class, 'profileUpdate'])->name('profile.update');
        Route::post('password-update', [AuthController::class, 'updatePassword'])->name('password.update');
    });
    
    // user
    Route::prefix('admin/user')->name('admin.user.')->group(function () {
        Route::get('/index', [UserController::class, 'index'])->name('index');
        Route::get('/data', [UserController::class, 'getData'])->name('data');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserController::class, 'delete'])->name('delete');
        Route::get('/track/{id}', [UserController::class, 'track'])->name('track');
        // Route::post('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::get('/assign-role/{id}', [UserController::class, 'assignRole'])->name('assignRole');
        Route::post('/store-role/{id}', [UserController::class, 'storeRole'])->name('storeRole');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('delete');
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
        Route::get('/track/{id}', [PermissionController::class, 'track'])->name('track');
        // Route::post('/create', [PermissionController::class, 'create'])->name('create');
        Route::post('/store', [PermissionController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PermissionController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [PermissionController::class, 'destroy'])->name('delete');
    });
    
    // approval flow
    Route::prefix('admin/setting-approval-flow')->name('admin.setting.approval.flow.')->group(function () {
        Route::get('/data', [ApprovalFlowController::class, 'getData'])->name('data');
        Route::get('/index', [ApprovalFlowController::class, 'index'])->name('index');
        Route::get('/show/{id}', [ApprovalFlowController::class, 'show'])->name('show');
        Route::get('/create', [ApprovalFlowController::class, 'create'])->name('create');
        Route::post('/store', [ApprovalFlowController::class, 'store'])->name('store');
        Route::get('/dit/{id}', [ApprovalFlowController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [ApprovalFlowController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ApprovalFlowController::class, 'destroy'])->name('delete');

        // Route::get('/assign/{id}', [ApprovalFlowStepsController::class, 'assignFlowSteps'])->name('assign');
        // Route::post('/store-assign/{id}', [ApprovalFlowStepsController::class, 'storeAssignFlowSteps'])->name('storeAssign');
    });

    // approval flow steps
    Route::prefix('admin/setting-approval-flow-steps')->name('admin.setting.approval.flow.steps.')->group(function () {
        Route::get('/data/{id}', [ApprovalFlowStepsController::class, 'getData'])->name('data');
        Route::get('/index/{id}', [ApprovalFlowStepsController::class, 'index'])->name('index');
        Route::get('/show/{id}', [ApprovalFlowStepsController::class, 'show'])->name('show');
        Route::get('/create', [ApprovalFlowStepsController::class, 'create'])->name('create');
        Route::post('/store', [ApprovalFlowStepsController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ApprovalFlowStepsController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [ApprovalFlowStepsController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ApprovalFlowStepsController::class, 'destroy'])->name('delete');
    });

    // // approval flow approver
    Route::prefix('admin/setting-approval-flow-steps-approver')->name('admin.setting.approval.flow.steps.approver.')->group(function () {
        Route::get('/data/{id}', [ApprovalFlowStepsApproverController::class, 'getData'])->name('data');
        Route::get('/index/{id}', [ApprovalFlowStepsApproverController::class, 'index'])->name('index');
        Route::get('/show/{id}', [ApprovalFlowStepsApproverController::class, 'show'])->name('show');
        Route::get('/create', [ApprovalFlowStepsApproverController::class, 'create'])->name('create');
        Route::post('/store', [ApprovalFlowStepsApproverController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ApprovalFlowStepsApproverController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [ApprovalFlowStepsApproverController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ApprovalFlowStepsApproverController::class, 'destroy'])->name('delete');
    });


    // role 
    Route::prefix('admin/setting-log')->name('admin.setting.log.')->group(function () {
        Route::get('/data', [LogActivityController::class, 'getData'])->name('data');
        Route::get('/index', [LogActivityController::class, 'index'])->name('index');
        Route::get('/show/{id}', [LogActivityController::class, 'show'])->name('show');
    });
    
    //view log
    // Route::get('admin/setting-log/index',[AuthController::class, 'viewLog'])->name('admin.setting.log.index');
    
    // Master
        //Structur
        Route::prefix('admin/structure-main-corp')->name('admin.structure.corp.')->group(function () {
            Route::get('/data', [MainCorporateController::class, 'getData'])->name('data');
            Route::get('/index', [MainCorporateController::class, 'index'])->name('index');
            Route::get('/show/{id}', [MainCorporateController::class, 'show'])->name('show');
            Route::get('/create', [MainCorporateController::class, 'create'])->name('create');
            Route::post('/store', [MainCorporateController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [MainCorporateController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [MainCorporateController::class, 'update'])->name('update');
            Route::get('/track/{id}', [MainCorporateController::class, 'track'])->name('track');
            Route::delete('/delete/{id}', [MainCorporateController::class, 'destroy'])->name('delete');
        });
    
        Route::prefix('admin/structure-direksi')->name('admin.structure.direksi.')->group(function () {
            Route::get('/data', [DireksiController::class, 'getData'])->name('data');
            Route::get('/index', [DireksiController::class, 'index'])->name('index');
            Route::get('/show/{id}', [DireksiController::class, 'show'])->name('show');
            Route::get('/create', [DireksiController::class, 'create'])->name('create');
            Route::post('/store', [DireksiController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [DireksiController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [DireksiController::class, 'update'])->name('update');
            Route::get('/track/{id}', [MainCorporateController::class, 'track'])->name('track');
            Route::delete('/delete/{id}', [DireksiController::class, 'destroy'])->name('delete');
        });
    
        Route::prefix('admin/structure-bagian')->name('admin.structure.bagian.')->group(function () {
            Route::get('/data', [BagianController::class, 'getData'])->name('data');
            Route::get('/index', [BagianController::class, 'index'])->name('index');
            Route::get('/show/{id}', [BagianController::class, 'show'])->name('show');
            Route::get('/create', [BagianController::class, 'create'])->name('create');
            Route::post('/store', [BagianController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [BagianController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [BagianController::class, 'update'])->name('update');
            Route::get('/track/{id}', [MainCorporateController::class, 'track'])->name('track');
            Route::delete('/delete/{id}', [BagianController::class, 'destroy'])->name('delete');
        });

        Route::prefix('admin/structure-position')->name('admin.structure.position.')->group(function () {
            Route::get('/data', [PositionController::class, 'getData'])->name('data');
            Route::get('/index', [PositionController::class, 'index'])->name('index');
            Route::get('/show/{id}', [PositionController::class, 'show'])->name('show');
            Route::get('/create', [PositionController::class, 'create'])->name('create');
            Route::post('/store', [PositionController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [PositionController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [PositionController::class, 'update'])->name('update');
            Route::get('/track/{id}', [PositionController::class, 'track'])->name('track');
            // Route::get('/track/{id}', [MainCorporateController::class, 'track'])->name('track');
            Route::delete('/delete/{id}', [PositionController::class, 'destroy'])->name('delete');
        });


        // blog controller
        Route::prefix('admin/master-blog')->name('admin.master.blog.')->group(function () {
            Route::get('/data', [BlogController::class, 'getData'])->name('data');
            Route::get('/index', [BlogController::class, 'index'])->name('index');
            Route::get('/show/{id}', [BlogController::class, 'show'])->name('show');
            Route::get('/create', [BlogController::class, 'create'])->name('create');
            Route::post('/store', [BlogController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [BlogController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [BlogController::class, 'update'])->name('update');
            Route::get('/track/{id}', [BlogController::class, 'track'])->name('track');
            // Route::get('/track/{id}', [MainCorporateController::class, 'track'])->name('track');
            Route::delete('/delete/{id}', [BlogController::class, 'destroy'])->name('delete');
        });
    
        // Route::prefix('admin/structure-sub-bagian')->name('admin.structure.subBagian.')->group(function () {
        //     Route::get('/index', [SubBagianController::class, 'index'])->name('index');
        //     Route::get('/show/{id}', [SubBagianController::class, 'show'])->name('show');
        //     Route::get('/create', [SubBagianController::class, 'create'])->name('create');
        //     Route::post('/store', [SubBagianController::class, 'store'])->name('store');
        //     Route::get('/edit/{id}', [SubBagianController::class, 'edit'])->name('edit');
        //     Route::post('/update/{id}', [SubBagianController::class, 'update'])->name('update');
        //     Route::delete('/delete/{id}', [SubBagianController::class, 'destroy'])->name('delete');
        // });
    
        // Route::prefix('admin/structure-sub-sub-bagian')->name('admin.structure.subSubBagian.')->group(function () {
        //     Route::get('/index', [SubSubBagianController::class, 'index'])->name('index');
        //     Route::get('/show/{id}', [SubSubBagianController::class, 'show'])->name('show');
        //     Route::get('/create', [SubSubBagianController::class, 'create'])->name('create');
        //     Route::post('/store', [SubSubBagianController::class, 'store'])->name('store');
        //     Route::get('/edit/{id}', [SubSubBagianController::class, 'edit'])->name('edit');
        //     Route::post('/update/{id}', [SubSubBagianController::class, 'update'])->name('update');
        //     Route::delete('/delete/{id}', [SubSubBagianController::class, 'destroy'])->name('delete');
        // });
    
    // Perencanaan
    Route::prefix('admin/perencanaan')->name('admin.perencanaan.')->group(function () {
        Route::get('/data', [PerencanaanController::class, 'getData'])->name('data');
        Route::get('/index', [PerencanaanController::class, 'index'])->name('index');
        Route::get('/show/{id}', [PerencanaanController::class, 'show'])->name('show');
        Route::get('/create', [PerencanaanController::class, 'create'])->name('create');
        Route::post('/store', [PerencanaanController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PerencanaanController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [PerencanaanController::class, 'update'])->name('update');
        Route::get('/track/{id}', [PerencanaanController::class, 'track'])->name('track');
        Route::delete('/delete/{id}', [PerencanaanController::class, 'destroy'])->name('delete');

        Route::get('/submit/{id}', [PerencanaanController::class, 'submitGet'])->name('submit');
        Route::post('/submit-store/{id}', [PerencanaanController::class, 'submitStore'])->name('submit.store');
    });
    
});
// ajax
Route::get('admin/user/ajax/search-structure', [AjaxController::class, 'searchStructure'])->name('ajax.search.structure');
Route::get('/api/notifications/count', [AjaxController::class, 'getNotificationCount']);
Route::get('/notification/{id}/read', [AjaxController::class, 'read'])->name('notification.read');
Route::get('/notifications', [AuthController::class, 'profile']);

Route::get('/home', function () {
    return view('Auth.login');
})->name('dash.home');
