<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\External\Persistance\ORM\Absensi;
use Architecture\External\Persistance\ORM\Dosen;
use Architecture\External\Persistance\ORM\NPribadi;
use Architecture\Shared\Facades\Utility;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TesController extends Controller
{
    public function __construct(
    ) {}
    
    public function tes(){
        dd($_SERVER,$_REQUEST);
        return Hash::make("251423");

        // try {
        //     $id = [];
        //     $list_pegawai = NPribadi::select('nip')->get();
        //     foreach($list_pegawai as $pegawai){
        //         $search = DB::table('absen')
        //                 ->select('id', 'nip', 'nidn', 'tanggal', 'absen_masuk', 'absen_keluar')
        //                 ->where('tanggal', '2024-11-29')
        //                 ->whereNull('absen_masuk')
        //                 ->where('nip', $pegawai->nip)
        //                 ->orderByRaw('ROW_NUMBER() OVER (PARTITION BY COALESCE(nidn, nip) ORDER BY nidn ASC, nip DESC, id DESC)')
        //                 ->limit(1) // Ambil record dengan rank = 1
        //                 ->first();

        //         $check = DB::table('absen')
        //                     ->select('id', 'nip', 'nidn', 'tanggal', 'absen_masuk', 'absen_keluar')
        //                     ->where('tanggal', '2024-11-28')
        //                     ->where('nip', $pegawai->nip)
        //                     ->count();

        //         if($check==0 && $search!=null){
        //             $id[] = $search->id;
        //         }
        //     }
        //     $list_dosen = Dosen::select('nidn')->get();
        //     foreach($list_dosen as $dosen){
        //         $search = DB::table('absen')
        //                 ->select('id', 'nip', 'nidn', 'tanggal', 'absen_masuk', 'absen_keluar')
        //                 ->where('tanggal', '2024-11-29')
        //                 ->whereNull('absen_masuk')
        //                 ->where('nidn', $dosen->nidn)
        //                 ->orderByRaw('ROW_NUMBER() OVER (PARTITION BY COALESCE(nidn, nip) ORDER BY nidn ASC, nip DESC, id DESC)')
        //                 ->limit(1) // Ambil record dengan rank = 1
        //                 ->first();

        //         $check = DB::table('absen')
        //                     ->select('id', 'nip', 'nidn', 'tanggal', 'absen_masuk', 'absen_keluar')
        //                     ->where('tanggal', '2024-11-28')
        //                     ->where('nidn', $dosen->nidn)
        //                     ->count();

        //         if($check==0 && $search!=null){
        //             $id[] = $search->id;
        //         }
        //     }
        //     return json_encode($id);
        //     echo "success create absent"; 
        // } catch (\Throwable $th) {
        //     throw $th;
        // }

        // $menit = str_pad(rand(0,59),  2, "0", STR_PAD_LEFT);
        // $jam = str_pad((int) $menit>15? 7:8,  2, "0", STR_PAD_LEFT);

        // try {
        //     DB::beginTransaction();
        //         Absensi::where('tanggal',"2024-11-28")
        //                 ->whereIn('nip',[
        //                     4102302214,
        //                     207241098534,
        //                     2110718458,
        //                     2111018464,
        //                     2110816441,
        //                     2110121489,
        //                     2110718457,
        //                     2110816439,
        //                     4102206194,
        //                     4102302215,
        //                     2110816436
        //                 ])
        //                 ->update(['absen_masuk' => "2024-11-28 $jam:$menit:00"]);
        //     DB::commit();
        //     echo "success create absent"; 
        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     throw $th;
        // }
        
        // $nip = "4102302214";
        // $nidn = "0409098601";

        // $presensiData = Absensi::with([
        //     'Pribadi' => function ($query) {
        //         $query->select('nip', 'nama');
        //     },
        //     'Dosen' => function ($query) {
        //         $query->with([
        //             'Fakultas' => function ($fakultasQuery) {
        //                 $fakultasQuery->select('kode_fakultas', 'nama_fakultas');
        //             },
        //             'Prodi' => function ($prodiQuery) {
        //                 $prodiQuery->select('kode_prodi', 'nama_prodi');
        //             }
        //         ])->select('NIDN', 'kode_fak', 'kode_prodi', 'nama_dosen');
        //     },
        //     'Pribadi.Pengangkatan' => function ($pengangkatanQuery) {
        //         $pengangkatanQuery->where('status_n_pengangkatan', 'berlaku')
        //             ->select('nip', 'unit_kerja');
        //     }
        // ])->select(
        //     'id', 
        //     'nidn', 
        //     'nip', 
        //     'tanggal', 
        //     'absen_masuk', 
        //     'absen_keluar', 
        //     'catatan_telat', 
        //     'catatan_pulang', 
        //     'otomatis_keluar', 
        //     'created_at', 
        //     'updated_at'
        // )
        // // ->where(DB::raw('YEAR(tanggal)'),date('Y'))
        // // ->where('nip_pegawai',$nip)
        // // ->orWhere('nip_dosen',$nip)
        // // ->orderBy('absen_masuk','DESC')
        // ->get();

        // // Map the result to match the desired format
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

        // return response()->json($mappedData);
    }
}
