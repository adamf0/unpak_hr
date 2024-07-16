<?php

namespace Architecture\External\Datatable\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Cuti\List\GetAllActiveCutiQuery;
use Architecture\Domain\Enum\FormatDate;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;
use Architecture\Shared\Facades\Utility;

class DatatableActiveCutiController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function index(Request $request){
        $nidn = $request->has('nidn')? $request->query('nidn'):null;
        $nip = $request->has('nip')? $request->query('nip'):null;
        $q = new GetAllActiveCutiQuery($nidn,$nip);
        // $q->SetOffset($request->get('start')??null)->SetLimit($request->get('length')??null);
        
        $listCuti = $this->queryBus->ask($q);
        $listCuti = $listCuti->map(function ($item){
                        return (object)[
                            "id"=>$item->GetId(),
                            "nama" => match(true){
                                !is_null($item->GetDosen()) && is_null($item->GetPegawai())=>$item->GetDosen()->GetNIDN()." - ".$item->GetDosen()->GetNama(),
                                is_null($item->GetDosen()) && !is_null($item->GetPegawai())=>$item->GetPegawai()->GetNIP()." - ".$item->GetPegawai()->GetNama(),
                                default=>"NA",
                            },
                            "jenis_cuti"=>$item->GetJenisCuti()?->GetNama()??"-",
                            "lama_cuti"=>$item->GetLamaCuti(),
                            "tanggal_mulai"=>$item->GetTanggalMulai()->toFormat(FormatDate::LDFY),
                            "tanggal_akhir"=>$item->GetTanggalAkhir()?->toFormat(FormatDate::LDFY),
                            "tujuan"=>$item->GetTujuan(),
                            "dokumen"=>empty($item->GetDokumen())? "":[
                                "file"=>$item->GetDokumen(),
                                "url"=>Utility::loadAsset('dokumen_cuti/'.rawurlencode($item->GetDokumen())),
                            ],
                            "verifikator_nidn"=>$item->GetVerifikasi()?->GetNidn(),
                            "verifikator_nip"=>$item->GetVerifikasi()?->GetNip(),
                            "status"=>$item->GetStatus(),
                            "catatan"=>$item->GetCatatan(),
                        ];
                    });
        
        return DataTables::of($listCuti)
        ->addIndexColumn()
        ->addColumn('tanggal_awal_akhir', function ($row) {
            $render = $row->tanggal_akhir==null || $row->tanggal_akhir==$row->tanggal_mulai? $row->tanggal_mulai : "{$row->tanggal_mulai} - {$row->tanggal_akhir}";
            return $render;
        })->rawColumns(['action'])
        ->make(true);
    }
}
