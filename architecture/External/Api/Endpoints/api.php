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

use Architecture\External\Api\Controller\ApiInfoController;
use Architecture\External\Api\Controller\ApiInfoDashboardController;
use Architecture\External\Api\Controller\ApiKalendarController;
use Architecture\External\Api\Controller\ApiPresensiController;
use Architecture\External\Persistance\ORM\Absensi;
use Architecture\External\Persistance\ORM\Dosen;
use Architecture\External\Persistance\ORM\NPribadi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('kalendar/{tahun}/{format}', [ApiKalendarController::class,'index'])->name('api.kalendar.index');
Route::get('info_dashboard/{type}/{id}', [ApiInfoDashboardController::class,'index'])->name('api.infoDashboard.index');
Route::post('presensi', [ApiPresensiController::class,'index'])->name('api.presensi.index');
Route::post('info_dosen_pegawai', [ApiInfoController::class,'index'])->name('api.Info.InfoDosenPegawai');

Route::get('tes', function(){
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