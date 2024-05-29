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

use Architecture\External\Select2\Controller\Select2JenisCutiController;
use Architecture\External\Select2\Controller\Select2JenisIzinController;
use Architecture\External\Select2\Controller\Select2JenisSPPDController;
use Architecture\External\Select2\Controller\Select2PresensiController;
use Illuminate\Support\Facades\Route;

Route::get('jenis_cuti', [Select2JenisCutiController::class,'List'])->name('select2.JenisCuti.List');
Route::get('jenis_izin', [Select2JenisIzinController::class,'List'])->name('select2.JenisIzin.List');
Route::get('jenis_sppd', [Select2JenisSPPDController::class,'List'])->name('select2.JenisSPPD.List');
Route::get('tanggal_absen', [Select2PresensiController::class,'List'])->name('select2.Presensi.List');