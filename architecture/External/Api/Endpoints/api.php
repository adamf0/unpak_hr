<?php

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

use Architecture\External\Api\Controller\ApiInfoDashboardController;
use Architecture\External\Api\Controller\ApiKalendarController;
use Architecture\External\Api\Controller\ApiPresensiController;
use Illuminate\Support\Facades\Route;

Route::get('kalendar/{tahun}/{format}', [ApiKalendarController::class,'index'])->name('api.kalendar.index');
Route::get('info_dashboard/{type}/{id}', [ApiInfoDashboardController::class,'index'])->name('api.infoDashboard.index');
Route::post('presensi', [ApiPresensiController::class,'index'])->name('api.presensi.index');