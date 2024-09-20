<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\External\Persistance\ORM\Absensi;
use Architecture\Shared\Facades\Utility;
use Illuminate\Support\Facades\DB;

class TesController extends Controller
{
    public function __construct(
    ) {}
    
    public function tes(){
        try {
            $nip = "4102302214";
            $nidn = "0409098601";

            $presensiData = Absensi::with([
                'Pribadi' => function ($query) {
                    $query->select('nip', 'nama');
                },
                // 'Dosen' => function ($query) {
                //     $query->with([
                //         'Fakultas' => function ($fakultasQuery) {
                //             $fakultasQuery->select('kode_fakultas', 'nama_fakultas');
                //         },
                //         'Prodi' => function ($prodiQuery) {
                //             $prodiQuery->select('kode_prodi', 'nama_prodi');
                //         }
                //     ])->select('NIDN', 'kode_fak', 'kode_prodi', 'nama_dosen');
                // },
                // 'Pribadi.Pengangkatan' => function ($pengangkatanQuery) {
                //     $pengangkatanQuery->where('status_n_pengangkatan', 'berlaku')
                //         ->select('nip', 'unit_kerja');
                // }
            ])->select(
                'id', 
                'nidn', 
                'nip', 
                'tanggal', 
                'absen_masuk', 
                'absen_keluar', 
                'catatan_telat', 
                'catatan_pulang', 
                'otomatis_keluar', 
                'created_at', 
                'updated_at'
            )
            // ->where(DB::raw('YEAR(tanggal)'),date('Y'))
            // ->where('nip_pegawai',$nip)
            // ->orWhere('nip_dosen',$nip)
            // ->orderBy('absen_masuk','DESC')
            ->get();

            // Map the result to match the desired format
            // $mappedData = $presensiData->reduce(function($carry,$item) {
            //     $carry[] = [
            //         'nip_pegawai' => $item?->Pribadi?->nip ?? null,
            //         'nama_pegawai' => $item?->Pribadi?->nama ?? null,
            //         'nip_dosen' => $item?->Dosen?->nip ?? null,
            //         'nidn_dosen' => $item?->Dosen?->nidn ?? null,
            //         'nama_dosen' => $item?->Dosen?->nama_dosen ?? null,
            //         'kode_fakultas' => $item?->Dosen?->Fakultas?->kode_fakultas ?? null,
            //         'nama_fakultas' => $item?->Dosen?->Fakultas?->nama_fakultas ?? null,
            //         'kode_prodi' => $item?->Dosen?->Prodi?->kode_prodi ?? null,
            //         'nama_prodi' => $item?->Dosen?->Prodi?->nama_prodi ?? null,
            //         'unit_kerja' => $item?->Pribadi?->Pengangkatan?->unit_kerja ?? null,
            //         'status' => $item?->Pribadi?->Pengangkatan?->status,
            //         'id' => $item?->id,
            //         'tanggal' => $item?->tanggal,
            //         'absen_masuk' => $item?->absen_masuk,
            //         'absen_keluar' => $item?->absen_keluar,
            //         'catatan_telat' => $item?->catatan_telat,
            //         'catatan_pulang' => $item?->catatan_pulang,
            //         'otomatis_keluar' => $item?->otomatis_keluar,
            //         'created_at' => $item?->created_at,
            //         'updated_at' => $item?->updated_at,
            //     ];

            //     return $carry;
            // });

            return response()->json($presensiData);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
