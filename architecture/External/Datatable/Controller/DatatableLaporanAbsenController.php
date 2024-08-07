<?php

namespace Architecture\External\Datatable\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\LaporanAbsen\List\GetAllLaporanAbsenQuery;
use Architecture\Shared\TypeData;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;

class DatatableLaporanAbsenController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function index(Request $request){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time','-1');        
        
        $nidn = $request->has('nidn')? $request->query('nidn'):null;
        $nip = $request->has('nip')? $request->query('nip'):null;
        $level = $request->has('level')? $request->query('level'):null;
        $type = $request->has('type')? $request->query('type'):null;
        $tanggal_awal = $request->has('tanggal_awal')? $request->query('tanggal_awal'):null;
        $tanggal_akhir = $request->has('tanggal_akhir')? $request->query('tanggal_akhir'):null;

        $laporan = $this->queryBus->ask(new GetAllLaporanAbsenQuery(null,null,$tanggal_awal,$tanggal_akhir,$type,TypeData::Default));
        $list_data = collect(isset($laporan["list_data"])? $laporan["list_data"]:[]);
        // $list_data = collect(isset($laporan["list_data"])? $laporan["list_data"]:[])->filter(function($item) use($type){
        //     return match($type){
        //         "dosen"=>$item['type']=="dosen",
        //         "tendik"=>$item['type']=="pegawai",
        //         default=>$item
        //     };
        // })->values();

        $table = DataTables::of($list_data)
        ->addIndexColumn()
        ->addColumn('nama', function ($row) {
            $row = (object) $row;
            if($row->type=="dosen"){
                $nama = $row->pengguna->nama_dosen??"NA";
                $kode = $row->pengguna->NIDN??"NA";
                return "{$nama} - {$kode}";
            } else if($row->type=="pegawai"){
                $nama = $row->pengguna->nama??"NA";
                $kode = $row->pengguna->nip??"NA";
                return "{$nama} - {$kode}";
            } else{
                $nama = "NA";
                $kode = "NA";
                return "{$nama} - {$kode}";
            }
        })
        ->rawColumns(['nama'])
        ->make(true);

        return $table;
    }
}
