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

use Architecture\External\Api\Controller\ApiCutiController;
use Architecture\External\Api\Controller\ApiInfoController;
use Architecture\External\Api\Controller\ApiInfoDashboardController;
use Architecture\External\Api\Controller\ApiIzinController;
use Architecture\External\Api\Controller\ApiKalendarController;
use Architecture\External\Api\Controller\ApiKlaimAbsenController;
use Architecture\External\Api\Controller\ApiPresensiController;
use Architecture\External\Api\Controller\ApiSPPDController;
use Architecture\External\Api\Controller\ApiSlipGajiController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('kalendar/{tahun}/{format}', [ApiKalendarController::class,'index'])->name('api.kalendar.index');
Route::get('info_dashboard/{type}/{id}', [ApiInfoDashboardController::class,'index'])->name('api.infoDashboard.index');
Route::post('presensi', [ApiPresensiController::class,'index'])->name('api.presensi.index');
Route::post('info_dosen_pegawai', [ApiInfoController::class,'index'])->name('api.Info.InfoDosenPegawai');

Route::post('cuti/approval/reject', [ApiCutiController::class,'reject'])->name('api.cuti.reject');
Route::post('izin/approval/reject', [ApiIzinController::class,'reject'])->name('api.izin.reject');
Route::post('sppd/approval/approval', [ApiSPPDController::class,'approval'])->name('api.sppd.approval');
Route::post('sppd/approval/reject', [ApiSPPDController::class,'reject'])->name('api.sppd.reject');
Route::post('klaim_absen/approval/reject', [ApiKlaimAbsenController::class,'reject'])->name('api.klaim_absen.reject');
Route::post('slip_gaji', [ApiSlipGajiController::class,'index'])->name('api.slip_gaji.index');

Route::get('source-absen', function(){
    return response()->json(DB::table('laporan_merge_absen_izin_cuti')->get());
    // try {
    //     DB::beginTransaction();
    //     $list_pegawai = NPribadi::select('nip')->get();
    //     foreach($list_pegawai as $pegawai){
    //         Absensi::updateOrCreate(
    //             ['nip' => $pegawai->nip, 'tanggal' => now()],
    //             ['tanggal' => now()]
    //         );
    //     }
    //     $list_dosen = Dosen::select('nidn')->get();
    //     foreach($list_dosen as $dosen){
    //         Absensi::updateOrCreate(
    //             ['nidn' => $dosen->nidn, 'tanggal' => now()],
    //             ['tanggal' => now()]
    //         );
    //     }
    //     DB::commit();
    //     echo "success create absent"; 
    // } catch (\Throwable $th) {
    //     DB::rollBack();
    //     throw $th;
    // }
});