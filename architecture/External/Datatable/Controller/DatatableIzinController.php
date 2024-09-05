<?php

namespace Architecture\External\Datatable\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Izin\List\GetAllIzinQuery;
use Architecture\Domain\Enum\FormatDate;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;
use Architecture\Shared\Facades\Utility;

class DatatableIzinController extends Controller
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
        $q = new GetAllIzinQuery($nidn,$nip,$verifikasi);
        // $q->SetOffset($request->get('start')??null)->SetLimit($request->get('length')??null);
        
        $listIzin = $this->queryBus->ask($q);
        $listIzin = $listIzin->filter(function($item) use($level,$type,$verifikasi,$nidn,$nip){
                        $rule1 = (
                            (!empty($item->GetVerifikasi()?->GetNidn()) && $item->GetVerifikasi()?->GetNidn()==$nidn) ||
                            (!empty($item->GetVerifikasi()?->GetNip()) && $item->GetVerifikasi()?->GetNip()==$nip)
                        );
                        if($verifikasi){
                            return $item;
                        } else if(in_array($level, ["dosen","pegawai"])){
                            return $item;
                            // return 
                            // ($type=="dosen" && !empty($item->GetDosen()) && $item->GetDosen()?->GetNidn()==$nidn) || 
                            // ($type=="tendik" && !empty($item->GetPegawai()) && $item->GetPegawai()?->GetNip()==$nip);
                        } else {
                            return in_array($item->GetStatus(), ["menunggu verifikasi sdm","terima sdm","tolak sdm"]);
                        }
                    })
                    ->map(function($item) use($level,$verifikasi){
                        return match(true){
                            in_array($level,["pegawai","dosen"]) && !$verifikasi=>(object)[
                                "id" => $item->GetId(),
                                "tanggal_pengajuan" => $item->GetTanggalPengajuan()->toFormat(FormatDate::LDFY),
                                "tujuan" => $item->GetTujuan(),
                                "jenis_izin" => $item->GetJenisIzin()?->GetNama(),
                                "dokumen"=>empty($item->GetDokumen())? "":[
                                    "file"=>$item->GetDokumen(),
                                    "url"=>Utility::loadAsset('dokumen_izin/'.rawurlencode($item->GetDokumen())),
                                ],
                                "verifikator_nidn"=>$item->GetVerifikasi()?->GetNidn(),
                                "verifikator_nip"=>$item->GetVerifikasi()?->GetNip(),
                                "catatan" => $item->GetCatatan(),
                                "status" => $item->GetStatus(),
                            ],
                            default=>(object)[
                                "id" => $item->GetId(),
                                "nama" => match(true){
                                    !is_null($item->GetDosen()) && is_null($item->GetPegawai())=>$item->GetDosen()->GetNIDN()." - ".$item->GetDosen()->GetNama(),
                                    is_null($item->GetDosen()) && !is_null($item->GetPegawai())=>$item->GetPegawai()->GetNIP()." - ".$item->GetPegawai()->GetNama(),
                                    default=>"NA",
                                },
                                "tanggal_pengajuan" => $item->GetTanggalPengajuan()->toFormat(FormatDate::LDFY),
                                "tujuan" => $item->GetTujuan(),
                                "jenis_izin" => $item->GetJenisIzin()?->GetNama(),
                                "dokumen"=>empty($item->GetDokumen())? "":[
                                    "file"=>$item->GetDokumen(),
                                    "url"=>Utility::loadAsset('dokumen_izin/'.rawurlencode($item->GetDokumen())),
                                ],
                                "verifikator_nidn"=>$item->GetVerifikasi()?->GetNidn(),
                                "verifikator_nip"=>$item->GetVerifikasi()?->GetNip(),
                                "catatan" => $item->GetCatatan(),
                                "status" => $item->GetStatus(),
                            ]
                        };
                    });
        
        return DataTables::of($listIzin)
        ->addIndexColumn()
        ->addColumn('action', function ($row) use($level,$verifikasi,$nidn,$nip){
            $render = '';
            if(in_array($level,['dosen','pegawai']) && !$verifikasi){
                if(empty($row->status) || in_array($row->status, ['menunggu','tolak atasan','tolak sdm'])){
                    $render = '<div class="row gap-2">
                    <a href="'.route('izin.edit',['id'=>$row->id]).'" class="btn btn-warning"><i class="bi bi-pencil-square"></i></a>
                    <a href="'.route('izin.delete',['id'=>$row->id]).'" class="btn btn-danger"><i class="bi bi-trash"></i></a>
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
                $render = '<div class="row gap-2">
                    <a href="'.route('izin.delete',['id'=>$row->id]).'" class="btn btn-danger"><i class="bi bi-trash"></i></a>

                    <a href="'.route('izin.approval',['id'=>$row->id,'type'=>($level=="sdm"? 'terima sdm':'menunggu verifikasi sdm')]).'" class="btn btn-success"><i class="bi bi-check-lg"></i></a>
                    <a href="#" class="btn btn-danger btn-reject"><i class="bi bi-x-lg"></i></a>
                    </div>
                ';
                // if($row->status=="terima"){
                // $render .= '<a href="#" class="btn btn-info btn-download-pdf"><i class="bi bi-file-earmark-pdf"></i></a>';
                // }
            } else if($row->status="terima sdm"){
                $render = '<div class="row gap-2">
                <a href="'.route('izin.delete',['id'=>$row->id]).'" class="btn btn-danger"><i class="bi bi-trash"></i></a>
                </div>
            ';
            }
            return $render;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
