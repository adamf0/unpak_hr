<?php

namespace Architecture\External\Datatable\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\SPPD\List\GetAllSPPDQuery;
use Architecture\Domain\Enum\FormatDate;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;
use Architecture\Shared\Facades\Utility;

class DatatableSPPDController extends Controller
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
        $q = new GetAllSPPDQuery($nidn,$nip);
        // $q->SetOffset($request->get('start')??null)->SetLimit($request->get('length')??null);
        
        $listSPPD = $this->queryBus->ask($q);
        $listSPPD = $listSPPD->filter(function($item) use($type){
                            return match($type){
                                "dosen"=>!is_null($item->GetDosen()),
                                "tendik"=>!is_null($item->GetPegawai()),
                                default=>$item
                            };
                        })
                        ->filter(function($item) use($level){
                            return match($level){
                                "sdm"=>in_array($item->GetStatus(), ['menunggu verifikasi sdm','tolak sdm','terima sdm']),
                                "warek"=>in_array($item->GetStatus(), ['menunggu','tolak warek','menunggu verifikasi sdm','tolak sdm','terima sdm']),
                                default=>$item,
                            };
                        })
                        ->map(function($item) use($level){
                            return match(true){
                                in_array($level,["pegawai","dosen"])=>(object)[
                                    "id"=>$item->GetId(),
                                    "nidn"=>$item->GetDosen()?->GetNIDN(),
                                    "nip"=>$item->GetPegawai()?->GetNIP(),
                                    "jenis_sppd"=> $item->GetJenisSPPD()?->GetNama(),
                                    "tanggal_berangkat"=> $item->GetTanggalBerangkat()->toFormat(FormatDate::LDFY),
                                    "tanggal_kembali"=> $item->GetTanggalKembali()->toFormat(FormatDate::LDFY),
                                    "tujuan"=> $item->GetTujuan(),
                                    "keterangan"=> $item->GetKeterangan(),
                                    "anggota"=> $item->GetListAnggota()->toArray(),
                                    "catatan"=> $item->GetCatatan(),
                                    "dokumen_anggaran"=> empty($item->GetDokumenAnggaran())? null:Utility::loadAsset("dokumen_anggaran/".$item->GetDokumenAnggaran()),
                                    "status"=> $item->GetStatus(),
                                ],
                                default=>(object)[
                                    "id"=>$item->GetId(),
                                    "nidn"=>$item->GetDosen()?->GetNIDN(),
                                    "nip"=>$item->GetPegawai()?->GetNIP(),
                                    "jenis_sppd"=> $item->GetJenisSPPD()?->GetNama(),
                                    "nama" => match(true){
                                        !is_null($item->GetDosen()) && is_null($item->GetPegawai())=>$item->Dosen()->GetNIP()." - ".$item->GetDosen()->GetNama(),
                                        is_null($item->GetDosen()) && !is_null($item->GetPegawai())=>$item->Pegawai()->GetNIP()." - ".$item->GetPegawai()->GetNama(),
                                        default=>"NA",
                                    },
                                    "tanggal_berangkat"=> $item->GetTanggalBerangkat()->toFormat(FormatDate::LDFY),
                                    "tanggal_kembali"=> $item->GetTanggalKembali()->toFormat(FormatDate::LDFY),
                                    "tujuan"=> $item->GetTujuan(),
                                    "keterangan"=> $item->GetKeterangan(),
                                    "anggota"=> $item->GetListAnggota()->toArray(),
                                    "catatan"=> $item->GetCatatan(),
                                    "dokumen_anggaran"=> empty($item->GetDokumenAnggaran())? null:Utility::loadAsset("dokumen_anggaran/".$item->GetDokumenAnggaran()),
                                    "status"=> $item->GetStatus(),
                                ],
                            };
                        });
        
        return DataTables::of($listSPPD)
        ->addIndexColumn()
        ->addColumn('action', function ($row) use($level,$nidn,$nip){
            $render = '';
            if(in_array($level,['dosen','pegawai'])){
                if(in_array($row->status, ['menunggu','tolak warek','tolak sdm']) && (($row->nidn==$nidn && !empty($nidn)) || ($row->nip==$nip && !empty($nip))) ){
                    $render = '<div class="row">
                    <a href="'.route('sppd.edit',['id'=>$row->id]).'" class="col-6 btn btn-warning"><i class="bi bi-pencil-square"></i></a>
                    <a href="'.route('sppd.delete',['id'=>$row->id]).'" class="mx-2 col-6 btn btn-danger"><i class="bi bi-trash"></i></a>
                    </div>
                    ';
                } else if($row->status=="terima sdm"){
                    $render = '<a href="#" class="btn btn-info btn-download-pengajuan-pdf"><i class="bi bi-file-earmark-pdf"></i></a>';
                }
            }
            else if(in_array($level, ['sdm','warek'])){
                $render = $level=="warek"? 
                            '<a href="#" class="btn btn-success btn-approve"><i class="bi bi-check-lg"></i></a>':
                            '<a href="'.route('sppd.approval',['id'=>$row->id,'level'=>$level??'-']).'" class="btn btn-success"><i class="bi bi-check-lg"></i></a>';
                
                $render .= '
                    <a href="#" class="ml-2 btn btn-danger btn-reject"><i class="bi bi-x-lg"></i></a>
                ';
                if(in_array($row->status, ['terima sdm','menunggu verifikasi sdm'])){
                    $render .= '<a href="#" class="ml-2 btn btn-secondary btn-download-anggaran"><i class="bi bi-wallet2"></i></a>';
                }
                if($row->status=="terima sdm"){
                    $render .= '<a href="#" class="btn btn-info btn-download-pengajuan-pdf"><i class="bi bi-file-earmark-pdf"></i></a>';
                }
            }
            
            return $render;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
