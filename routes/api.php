<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\WatersportsController;
use GuzzleHttp\Middleware;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



// php artisan make:middleware ApiTestMiddleware
// masukan di kernel.php juga
Route::group(['middleware' => 'apitest'], function () {
    Route::get('/data-category', [WatersportsController::class, 'getCategory']);
    Route::get('/data-packages', [WatersportsController::class, 'getPackages']);
    Route::get('/data-packages-search', [WatersportsController::class, 'getSearchPackages']);
});


// withMiddleware(function(Middleware $middleware) {
//     $middleware->redirectGuestsTo('/login');
//     $middleware->redirectGuestsTo(fn(Request $request) => route('login'))
// });