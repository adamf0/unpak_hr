<?php

namespace Architecture\External\Datatable\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\KlaimAbsen\List\GetAllKlaimAbsenQuery;
use Architecture\Domain\Enum\FormatDate;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;
use Architecture\Shared\Facades\Utility;

class DatatableKlaimAbsenController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function index(Request $request){
        $nidn = $request->has('nidn')? $request->query('nidn'):null;
        $nip = $request->has('nip')? $request->query('nip'):null;
        $level = $request->has('level')? $request->query('level'):null;
        
        $q = new GetAllKlaimAbsenQuery($nidn,$nip);
        // $q->SetOffset($request->get('start')??null)->SetLimit($request->get('length')??null);
        
        $listKlaimAbsen = $this->queryBus->ask($q);
        $listKlaimAbsen = $listKlaimAbsen->map(function($item) use($level){
            return match(true){
                in_array($level, ["pegawai","dosen"])=>(object)[
                    "id"=>$item->GetId(),
                    "tanggal"=>$item->GetPresensi()?->GetTanggal()?->toFormat(FormatDate::LDFY),
                    "jam_masuk"=>$item->GetPresensi()?->GetAbsenMasuk()?->toFormat(FormatDate::HIS),
                    "jam_keluar"=>$item->GetPresensi()?->GetAbsenKeluar()?->toFormat(FormatDate::HIS),
                    "jam_masuk_klaim"=>$item->GetJamMasuk(),
                    "jam_keluar_klaim"=>$item->GetJamKeluar(),
                    "tujuan"=>$item->GetTujuan(),
                    "dokumen"=>empty($item->GetDokumen())? "":[
                        "file"=>$item->GetDokumen(),
                        "url"=>Utility::loadAsset('dokumen_klaim_absen/'.$item->GetDokumen()),
                    ],
                    "status"=>$item->GetStatus(),
                    "catatan"=>$item->GetCatatan(),
                ],
                default=>(object)[
                    "id"=>$item->GetId(),
                    "nama" => match(true){
                        !is_null($item->GetDosen()) && is_null($item->GetPegawai())=>$item->GetDosen()->GetNama(),
                        is_null($item->GetDosen()) && !is_null($item->GetPegawai())=>$item->GetPegawai()->GetNama(),
                        default=>"NA",
                    },
                    "tanggal"=>$item->GetPresensi()?->GetTanggal()?->toFormat(FormatDate::LDFY),
                    "jam_masuk"=>$item->GetPresensi()?->GetAbsenMasuk()?->toFormat(FormatDate::HIS),
                    "jam_keluar"=>$item->GetPresensi()?->GetAbsenKeluar()?->toFormat(FormatDate::HIS),
                    "jam_masuk_klaim"=>$item->GetJamMasuk(),
                    "jam_keluar_klaim"=>$item->GetJamKeluar(),
                    "tujuan"=>$item->GetTujuan(),
                    "dokumen"=>empty($item->GetDokumen())? "":[
                        "file"=>$item->GetDokumen(),
                        "url"=>Utility::loadAsset('dokumen_klaim_absen/'.$item->GetDokumen()),
                    ],
                    "status"=>$item->GetStatus(),
                    "catatan"=>$item->GetCatatan(),
                ]
            };
        });
        
        return DataTables::of($listKlaimAbsen)
        ->addIndexColumn()
        ->addColumn('tanggal_jam_masuk_keluar', function ($row) {
            $render = $row->tanggal."<br>"."<span class='badge bg-success'>".$row->jam_masuk."</span> - <span class='badge bg-danger'>".(empty($row->jam_keluar)? "masih masuk":$row->jam_keluar)."</span>";
            return $render;
        })
        ->addColumn('action', function ($row) use($level){
            $render = '';
            if(in_array($level,['dosen','pegawai'])){
                if(in_array($row->status, ['menunggu','tolak'])){
                    $render = '<div class="row">
                    <a href="'.route('klaim_absen.edit',['id'=>$row->id]).'" class="col-6 btn btn-warning"><i class="bi bi-pencil-square"></i></a>
                    <a href="'.route('klaim_absen.delete',['id'=>$row->id]).'" class="mx-2 col-6 btn btn-danger"><i class="bi bi-trash"></i></a>
                    </div>
                    ';
                } 
                // else {
                //     $render = '<a href="#" class="btn btn-info btn-download-pdf"><i class="bi bi-file-earmark-pdf"></i></a>';
                // }
            }
            else if($level=="sdm"){
                $render = '
                    <a href="'.route('klaim_absen.approval',['id'=>$row->id,'type'=>'terima']).'" class="btn btn-success"><i class="bi bi-check-lg"></i></a>
                    <a href="#" class="mx-2 btn btn-danger btn-reject"><i class="bi bi-x-lg"></i></a>
                ';
                // if($row->status=="terima"){
                // $render .= '<a href="#" class="btn btn-info btn-download-pdf"><i class="bi bi-file-earmark-pdf"></i></a>';
                // }
            }
            return $render;
        })
        ->rawColumns(['action','tanggal_jam_masuk_keluar'])
        ->make(true);
    }
}
