<?php

namespace Architecture\External\Datatable\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Cuti\List\GetAllCutiQuery;
use Architecture\Domain\Enum\FormatDate;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;
use Architecture\Shared\Facades\Utility;

class DatatableCutiController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function index(Request $request){
        $nidn = $request->has('nidn')? $request->query('nidn'):null;
        $nip = $request->has('nip')? $request->query('nip'):null;
        $level = $request->has('level')? $request->query('level'):null;
        $type = $request->has('type')? $request->query('type'):null;
        $verifikasi = $request->has('verifikasi')? (int) $request->query('verifikasi'):0;
        $q = new GetAllCutiQuery($nidn,$nip);
        // $q->SetOffset($request->get('start')??null)->SetLimit($request->get('length')??null);
        
        $listCuti = $this->queryBus->ask($q);
        $listCuti = $listCuti->filter(function($item) use($level,$type,$verifikasi,$nidn,$nip){
                        $rule1 = (
                            (!is_null($item->GetVerifikasi()?->GetNidn()) && $item->GetVerifikasi()?->GetNidn()==$nidn) ||
                            (!is_null($item->GetVerifikasi()?->GetNip()) && $item->GetVerifikasi()?->GetNip()==$nip)
                        );
                        if($verifikasi && in_array($level, ["dosen","pegawai"])){
                            return $rule1;
                        } else if(in_array($level, ["dosen","pegawai"])){
                            return ($type=="dosen" && !is_null($item->GetDosen())) || ($type=="tendik" && !is_null($item->GetPegawai()));
                        } else {
                            return (($type=="dosen" && !is_null($item->GetDosen())) || ($type=="tendik" && !is_null($item->GetPegawai()))) && in_array($item->GetStatus(), ["menunggu verifikasi sdm","terima sdm","tolak sdm"]);
                        }
                    })
                    ->map(function ($item) use($level){
                        return match(true){
                            in_array($level, ["pegawai","dosen"])=>(object)[
                                "id"=>$item->GetId(),
                                "jenis_cuti"=>$item->GetJenisCuti()?->GetNama()??"-",
                                "lama_cuti"=>$item->GetLamaCuti(),
                                "tanggal_mulai"=>$item->GetTanggalMulai()->toFormat(FormatDate::LDFY),
                                "tanggal_akhir"=>$item->GetTanggalAkhir()?->toFormat(FormatDate::LDFY),
                                "tujuan"=>$item->GetTujuan(),
                                "dokumen"=>empty($item->GetDokumen())? "":[
                                    "file"=>$item->GetDokumen(),
                                    "url"=>Utility::loadAsset('dokumen_cuti/'.$item->GetDokumen()),
                                ],
                                "verifikator_nidn"=>$item->GetVerifikasi()?->GetNidn(),
                                "verifikator_nip"=>$item->GetVerifikasi()?->GetNip(),
                                "status"=>$item->GetStatus(),
                                "catatan"=>$item->GetCatatan(),
                            ],
                            default=>(object)[
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
                                    "url"=>Utility::loadAsset('dokumen_cuti/'.$item->GetDokumen()),
                                ],
                                "verifikator_nidn"=>$item->GetVerifikasi()?->GetNidn(),
                                "verifikator_nip"=>$item->GetVerifikasi()?->GetNip(),
                                "status"=>$item->GetStatus(),
                                "catatan"=>$item->GetCatatan(),
                            ]
                        };
                    });
        
        return DataTables::of($listCuti)
        ->addIndexColumn()
        ->addColumn('tanggal_awal_akhir', function ($row) {
            $render = $row->tanggal_akhir==null || $row->tanggal_akhir==$row->tanggal_mulai? $row->tanggal_mulai : "{$row->tanggal_mulai} - {$row->tanggal_akhir}";
            return $render;
        })
        ->addColumn('action', function ($row) use($nidn, $nip, $level, $verifikasi){
            $render = '';
            if(in_array($level,['dosen','pegawai']) && !$verifikasi){
                if(empty($row->status) || in_array($row->status, ['menunggu','tolak atasan','tolak sdm'])){
                    $render = '<div class="row">
                    <a href="'.route('cuti.edit',['id'=>$row->id]).'" class="col-6 btn btn-warning"><i class="bi bi-pencil-square"></i></a>
                    <a href="'.route('cuti.delete',['id'=>$row->id]).'" class="mx-2 col-6 btn btn-danger"><i class="bi bi-trash"></i></a>
                    </div>
                    ';
                } 
                // else {
                //     $render = '<a href="#" class="btn btn-info btn-download-pdf"><i class="bi bi-file-earmark-pdf"></i></a>';
                // }
            }else if( 
                (
                    (!is_null($row->verifikator_nidn) && $row->verifikator_nidn==$nidn) ||
                    (!is_null($row->verifikator_nip) && $row->verifikator_nip==$nip)
                ) || 
                in_array($row->status, ["menunggu","menunggu verifikasi sdm"])){
                $render = '
                    <a href="'.route('cuti.approval',['id'=>$row->id,'type'=>($level=="sdm"? 'terima sdm':'menunggu verifikasi sdm')]).'" class="btn btn-success"><i class="bi bi-check-lg"></i></a>
                    <a href="#" class="mx-2 btn btn-danger btn-reject"><i class="bi bi-x-lg"></i></a>
                ';
                // if($row->status=="terima"){
                // $render .= '<a href="#" class="btn btn-info btn-download-pdf"><i class="bi bi-file-earmark-pdf"></i></a>';
                // }
            }
            return $render;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
