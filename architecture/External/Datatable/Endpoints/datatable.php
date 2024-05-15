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
use Architecture\External\Datatable\Controller\DatatableJenisCutiController;
use Architecture\External\Datatable\Controller\DatatableJenisizinController;
use Illuminate\Support\Facades\Route;

Route::get('cuti', [DatatableCutiController::class,'index'])->name('datatable.Cuti.index');
Route::get('jenis_cuti', [DatatableJenisCutiController::class,'index'])->name('datatable.JenisCuti.index');
Route::get('jenis_izin', [DatatableJenisizinController::class,'index'])->name('datatable.JenisIzin.index');