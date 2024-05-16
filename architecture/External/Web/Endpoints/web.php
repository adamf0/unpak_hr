<?php

use Architecture\External\Web\Controller\AuthController;
use Architecture\External\Web\Controller\CutiController;
use Architecture\External\Web\Controller\DashboardController;
use Architecture\External\Web\Controller\IzinController;
use Architecture\External\Web\Controller\JenisCutiController;
use Architecture\External\Web\Controller\JenisIzinController;
use Architecture\External\Web\Controller\JenisSPPDController;
use Architecture\External\Web\Controller\SPPDController;
use Architecture\External\Web\Controller\VideoKegiatanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [AuthController::class,'Authorization'])->name('auth.authorization');
Route::post('login', [AuthController::class,'Authentication'])->name('auth.authentication');
Route::get('logout', [AuthController::class,'Logout'])->name('auth.logout');

Route::middleware(['throwSession'])->group(function () {
    Route::get('dashboard', [DashboardController::class,'index'])->name('dashboard.index');
    
    Route::get('cuti', [CutiController::class,'index'])->name('cuti.index');
    Route::get('cuti/create', [CutiController::class,'create'])->name('cuti.create');
    Route::post('cuti/store', [CutiController::class,'store'])->name('cuti.store');
    Route::get('cuti/edit/{id}', [CutiController::class,'edit'])->name('cuti.edit');
    Route::post('cuti/update', [CutiController::class,'update'])->name('cuti.update');
    Route::get('cuti/delete/{id}', [CutiController::class,'delete'])->name('cuti.delete');
    Route::get('cuti/approval/{id}/{type}', [CutiController::class,'approval'])->name('cuti.approval');

    Route::get('jenis_cuti', [JenisCutiController::class,'index'])->name('jenis_cuti.index');
    Route::get('jenis_cuti/create', [JenisCutiController::class,'create'])->name('jenis_cuti.create');
    Route::post('jenis_cuti/store', [JenisCutiController::class,'store'])->name('jenis_cuti.store');
    Route::get('jenis_cuti/edit/{id}', [JenisCutiController::class,'edit'])->name('jenis_cuti.edit');
    Route::post('jenis_cuti/update', [JenisCutiController::class,'update'])->name('jenis_cuti.update');
    Route::get('jenis_cuti/delete/{id}', [JenisCutiController::class,'delete'])->name('jenis_cuti.delete');

    Route::get('izin', [IzinController::class,'index'])->name('izin.index');
    Route::get('izin/create', [IzinController::class,'create'])->name('izin.create');
    Route::post('izin/store', [IzinController::class,'store'])->name('izin.store');
    Route::get('izin/edit/{id}', [IzinController::class,'edit'])->name('izin.edit');
    Route::post('izin/update', [IzinController::class,'update'])->name('izin.update');
    Route::get('izin/delete/{id}', [IzinController::class,'delete'])->name('izin.delete');
    Route::get('izin/approval/{id}/{type}', [IzinController::class,'approval'])->name('izin.approval');

    Route::get('jenis_izin', [JenisIzinController::class,'index'])->name('jenis_izin.index');
    Route::get('jenis_izin/create', [JenisIzinController::class,'create'])->name('jenis_izin.create');
    Route::post('jenis_izin/store', [JenisIzinController::class,'store'])->name('jenis_izin.store');
    Route::get('jenis_izin/edit/{id}', [JenisIzinController::class,'edit'])->name('jenis_izin.edit');
    Route::post('jenis_izin/update', [JenisIzinController::class,'update'])->name('jenis_izin.update');
    Route::get('jenis_izin/delete/{id}', [JenisIzinController::class,'delete'])->name('jenis_izin.delete');

    Route::get('sppd', [SPPDController::class,'index'])->name('sppd.index');
    Route::get('sppd/create', [SPPDController::class,'create'])->name('sppd.create');
    Route::post('sppd/store', [SPPDController::class,'store'])->name('sppd.store');
    Route::get('sppd/edit/{id}', [SPPDController::class,'edit'])->name('sppd.edit');
    Route::post('sppd/update', [SPPDController::class,'update'])->name('sppd.update');
    Route::get('sppd/delete/{id}', [SPPDController::class,'delete'])->name('sppd.delete');

    Route::get('jenis_sppd', [JenisSPPDController::class,'index'])->name('jenis_sppd.index');
    Route::get('jenis_sppd/create', [JenisSPPDController::class,'create'])->name('jenis_sppd.create');
    Route::post('jenis_sppd/store', [JenisSPPDController::class,'store'])->name('jenis_sppd.store');
    Route::get('jenis_sppd/edit/{id}', [JenisSPPDController::class,'edit'])->name('jenis_sppd.edit');
    Route::post('jenis_sppd/update', [JenisSPPDController::class,'update'])->name('jenis_sppd.update');
    Route::get('jenis_sppd/delete/{id}', [JenisSPPDController::class,'delete'])->name('jenis_sppd.delete');
});
