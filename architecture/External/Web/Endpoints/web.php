<?php

use Architecture\External\Web\Controller\AuthController;
use Architecture\External\Web\Controller\CutiController;
use Architecture\External\Web\Controller\DashboardController;
use Architecture\External\Web\Controller\JenisCutiController;
use Architecture\External\Web\Controller\JenisIzinController;
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

    Route::get('jenis_cuti', [JenisCutiController::class,'index'])->name('jenis_cuti.index');
    Route::get('jenis_cuti/create', [JenisCutiController::class,'create'])->name('jenis_cuti.create');
    Route::post('jenis_cuti/store', [JenisCutiController::class,'store'])->name('jenis_cuti.store');
    Route::get('jenis_cuti/edit/{id}', [JenisCutiController::class,'edit'])->name('jenis_cuti.edit');
    Route::post('jenis_cuti/update', [JenisCutiController::class,'update'])->name('jenis_cuti.update');
    Route::get('jenis_cuti/delete/{id}', [JenisCutiController::class,'delete'])->name('jenis_cuti.delete');

    Route::get('jenis_izin', [JenisIzinController::class,'index'])->name('jenis_izin.index');
    Route::get('jenis_izin/create', [JenisIzinController::class,'create'])->name('jenis_izin.create');
    Route::post('jenis_izin/store', [JenisIzinController::class,'store'])->name('jenis_izin.store');
    Route::get('jenis_izin/edit/{id}', [JenisIzinController::class,'edit'])->name('jenis_izin.edit');
    Route::post('jenis_izin/update', [JenisIzinController::class,'update'])->name('jenis_izin.update');
    Route::get('jenis_izin/delete/{id}', [JenisIzinController::class,'delete'])->name('jenis_izin.delete');

    Route::get('videoKegiatan', [VideoKegiatanController::class,'index'])->name('videoKegiatan.index');
    Route::get('videoKegiatan/create', [VideoKegiatanController::class,'create'])->name('videoKegiatan.create');
    Route::post('videoKegiatan/store', [VideoKegiatanController::class,'store'])->name('videoKegiatan.store');
    Route::get('videoKegiatan/edit/{id}', [VideoKegiatanController::class,'edit'])->name('videoKegiatan.edit');
    Route::post('videoKegiatan/update', [VideoKegiatanController::class,'update'])->name('videoKegiatan.update');
    Route::get('videoKegiatan/delete/{id}', [VideoKegiatanController::class,'delete'])->name('videoKegiatan.delete');
});
