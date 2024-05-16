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

use Architecture\External\Datatable\Controller\DatatableCutiController;
use Architecture\External\Datatable\Controller\DatatableIzinController;
use Architecture\External\Datatable\Controller\DatatableJenisCutiController;
use Architecture\External\Datatable\Controller\DatatableJenisizinController;
use Architecture\External\Datatable\Controller\DatatableJenisSPPDController;
use Architecture\External\Datatable\Controller\DatatableSPPDController;
use Illuminate\Support\Facades\Route;

Route::get('cuti', [DatatableCutiController::class,'index'])->name('datatable.Cuti.index');
Route::get('izin', [DatatableIzinController::class,'index'])->name('datatable.Izin.index');
Route::get('sppd', [DatatableSPPDController::class,'index'])->name('datatable.SPPD.index');
Route::get('jenis_cuti', [DatatableJenisCutiController::class,'index'])->name('datatable.JenisCuti.index');
Route::get('jenis_izin', [DatatableJenisIzinController::class,'index'])->name('datatable.JenisIzin.index');
Route::get('jenis_sppd', [DatatableJenisSPPDController::class,'index'])->name('datatable.JenisSPPD.index');