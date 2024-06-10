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

use Architecture\External\Select2\Controller\Select2DosenController;
use Architecture\External\Select2\Controller\Select2DosenPegawaiController;
use Architecture\External\Select2\Controller\Select2JenisCutiController;
use Architecture\External\Select2\Controller\Select2JenisIzinController;
use Architecture\External\Select2\Controller\Select2JenisSPPDController;
use Architecture\External\Select2\Controller\Select2PegawaiController;
use Architecture\External\Select2\Controller\Select2PresensiController;
use Architecture\External\Select2\Controller\Select2VerifikatorController;
use Illuminate\Support\Facades\Route;

Route::get('dosen', [Select2DosenController::class,'List'])->name('select2.Dosen.List');
Route::get('pegawai', [Select2PegawaiController::class,'List'])->name('select2.Pegawai.List');
Route::get('dosen_pegawai', [Select2DosenPegawaiController::class,'List'])->name('select2.DosenPegawai.List');
Route::get('jenis_cuti', [Select2JenisCutiController::class,'List'])->name('select2.JenisCuti.List');
Route::get('jenis_izin', [Select2JenisIzinController::class,'List'])->name('select2.JenisIzin.List');
Route::get('jenis_sppd', [Select2JenisSPPDController::class,'List'])->name('select2.JenisSPPD.List');
Route::get('tanggal_absen', [Select2PresensiController::class,'List'])->name('select2.Presensi.List');
Route::get('list_pegawai', [Select2VerifikatorController::class,'List'])->name('select2.PegawaiV2.List');