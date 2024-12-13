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

// Route::middleware('auth:sanctum')->post('/user', function (Request $request) {
//     return $request->user();
// });

use Architecture\External\Datatable\Controller\DatatableActiveCutiController;
use Architecture\External\Datatable\Controller\DatatableCutiController;
use Architecture\External\Datatable\Controller\DatatableIzinController;
use Architecture\External\Datatable\Controller\DatatableJenisCutiController;
use Architecture\External\Datatable\Controller\DatatableJenisizinController;
use Architecture\External\Datatable\Controller\DatatableJenisSPPDController;
use Architecture\External\Datatable\Controller\DatatableKlaimAbsenController;
use Architecture\External\Datatable\Controller\DatatableLaporanAbsenController;
use Architecture\External\Datatable\Controller\DatatableMasterKalendarController;
use Architecture\External\Datatable\Controller\DatatablePenggunaController;
use Architecture\External\Datatable\Controller\DatatablePresensiController;
use Architecture\External\Datatable\Controller\DatatableSPPDController;
use Illuminate\Support\Facades\Route;

Route::post('pengguna', [DatatablePenggunaController::class,'index'])->name('datatable.Pengguna.index');
Route::post('master_kalendar', [DatatableMasterKalendarController::class,'index'])->name('datatable.MasterKalendar.index');
Route::post('cuti', [DatatableCutiController::class,'index'])->name('datatable.Cuti.index');
Route::post('cuti/active', [DatatableActiveCutiController::class,'index'])->name('datatable.ActiveCuti.index');
Route::post('izin', [DatatableIzinController::class,'index'])->name('datatable.Izin.index');
Route::post('sppd', [DatatableSPPDController::class,'index'])->name('datatable.SPPD.index');
Route::post('klaim_absen', [DatatableKlaimAbsenController::class,'index'])->name('datatable.KlaimAbsen.index');
Route::post('presensi', [DatatablePresensiController::class,'index'])->name('datatable.Presensi.index');
Route::post('jenis_cuti', [DatatableJenisCutiController::class,'index'])->name('datatable.JenisCuti.index');
Route::post('jenis_izin', [DatatableJenisIzinController::class,'index'])->name('datatable.JenisIzin.index');
Route::post('jenis_sppd', [DatatableJenisSPPDController::class,'index'])->name('datatable.JenisSPPD.index');
Route::post('laporan_absen', [DatatableLaporanAbsenController::class,'index'])->name('datatable.LaporanAbsen.index');