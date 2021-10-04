<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\MasterAreaController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MasterOptionController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\GroupSetupController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AuthController;

use App\Http\Middleware\CORS;

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

Route::post('login', [AuthController::class, 'login']);
Route::group(['prefix' => 'auth', 'middleware' => 'auth:sanctum'], function() {
    // manggil controller sesuai bawaan laravel 8
    Route::post('logout', [AuthController::class, 'logout']);
    // manggil controller dengan mengubah namespace di RouteServiceProvider.php biar bisa kayak versi2 sebelumnya
    Route::post('logoutall', [AuthController::class, 'logoutall']);
});

Route::post('getCombo', [MasterOptionController::class, 'combo_fill'])->middleware('auth:sanctum');
Route::post('generate', [MasterOptionController::class, 'generate']);

Route::post('MasterData', [MasterDataController::class, 'index']);
Route::post('MasterData/modalDataPut', [MasterDataController::class, 'shows']);
Route::post('MasterData/checkDataWithLanguage', [MasterDataController::class, 'check']);
Route::post('MasterData/createData', [MasterDataController::class, 'create'])->middleware('cors');
Route::put('MasterData/createData', [MasterDataController::class, 'update']);
Route::delete('MasterData/deleteData', [MasterDataController::class, 'delete']);


Route::post('Area', [MasterAreaController::class, 'index']);
Route::post('Area/modalDataPut', [MasterAreaController::class, 'shows']);
Route::post('Area/createData', [MasterAreaController::class, 'create'])->middleware('cors');
// for postman
// Route::post('Area/createData', [MasterAreaController::class, 'create']);
Route::put('Area/createData', [MasterAreaController::class, 'create']);
Route::delete('Area/deleteData', [MasterAreaController::class, 'delete']);

Route::post('Book', [BookController::class, 'index']);
Route::post('Book/modalDataPut', [BookController::class, 'shows']);
Route::post('Book/createData', [BookController::class, 'create'])->middleware('cors');
// for postman
// Route::post('Book/createData', [Bookcontroller::class, 'create']);
Route::put('Book/createData', [BookController::class, 'update']);
Route::delete('Book/deleteData', [BookController::class, 'delete']);

Route::post('School', [SchoolController::class, 'index']);
Route::post('School/modalDataPut', [SchoolController::class, 'shows']);
Route::post('School/createData', [SchoolController::class, 'create'])->middleware('cors');
Route::put('School/createData', [SchoolController::class, 'update']);
Route::delete('School/deleteData', [SchoolController::class, 'delete']);

Route::post('GroupSetup', [GroupSetupController::class, 'index']);
Route::post('GroupSetup/modalDataPut', [GroupSetupController::class, 'shows']);
Route::post('GroupSetup/setup', [GroupSetupController::class, 'setup']);
Route::post('GroupSetup/getAccess', [GroupSetupController::class, 'access']);
Route::post('GroupSetup/createData', [GroupSetupController::class, 'create']);
Route::put('GroupSetup/createData', [GroupSetupController::class, 'create']);
Route::delete('GroupSetup/deleteData', [GroupSetupController::class, 'delete']);

// Route::post('/login', [LoginController::class, 'check'])->middleware('cors');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
