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
        $verifikasi = $request->has('verifikasi')? (int) $request->query('verifikasi'):0;
        $q = new GetAllSPPDQuery($nidn,$nip);
        // $q->SetOffset($request->get('start')??null)->SetLimit($request->get('length')??null);
        
        $listSPPD = $this->queryBus->ask($q);
        $listSPPD = $listSPPD->filter(function($item) use($level,$type,$verifikasi,$nidn,$nip){
                                $rule1 = (
                                    (!empty($item->GetVerifikasi()?->GetNidn()) && $item->GetVerifikasi()?->GetNidn()==$nidn) ||
                                    (!empty($item->GetVerifikasi()?->GetNip()) && $item->GetVerifikasi()?->GetNip()==$nip)
                                );
                                if($verifikasi && in_array($level, ["dosen","pegawai"])){
                                    return $rule1;
                                } else if(in_array($level, ["dosen","pegawai"])){
                                    return $item;
                                    // return 
                                    // ($type=="dosen" && !empty($item->GetDosen()) && $item->GetDosen()?->GetNidn()==$nidn) || 
                                    // ($type=="tendik" && !empty($item->GetPegawai()) && $item->GetPegawai()?->GetNip()==$nip);
                                } else {
                                    return (
                                        ($type=="dosen" && !empty($item->GetDosen())) || 
                                        ($type=="tendik" && !empty($item->GetPegawai()))
                                    ) && in_array($item->GetStatus(), ["menunggu verifikasi sdm","terima sdm","tolak sdm"]);
                                }
                            })
                            ->map(function($item) use($level,$verifikasi){
                                return match(true){
                                    in_array($level,["pegawai","dosen"]) && !$verifikasi=>(object)[
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
                                        "verifikator_nidn"=>$item->GetVerifikasi()?->GetNidn(),
                                        "verifikator_nip"=>$item->GetVerifikasi()?->GetNip(),
                                        "status"=> $item->GetStatus(),
                                    ],
                                    default=>(object)[
                                        "id"=>$item->GetId(),
                                        "nidn"=>$item->GetDosen()?->GetNIDN(),
                                        "nip"=>$item->GetPegawai()?->GetNIP(),
                                        "jenis_sppd"=> $item->GetJenisSPPD()?->GetNama(),
                                        "nama" => match(true){
                                            !is_null($item->GetDosen()) && is_null($item->GetPegawai())=>$item->GetDosen()->GetNIDN()." - ".$item->GetDosen()->GetNama(),
                                            is_null($item->GetDosen()) && !is_null($item->GetPegawai())=>$item->GetPegawai()->GetNIP()." - ".$item->GetPegawai()->GetNama(),
                                            default=>"NA",
                                        },
                                        "tanggal_berangkat"=> $item->GetTanggalBerangkat()->toFormat(FormatDate::LDFY),
                                        "tanggal_kembali"=> $item->GetTanggalKembali()->toFormat(FormatDate::LDFY),
                                        "tujuan"=> $item->GetTujuan(),
                                        "keterangan"=> $item->GetKeterangan(),
                                        "anggota"=> $item->GetListAnggota()->toArray(),
                                        "catatan"=> $item->GetCatatan(),
                                        "dokumen_anggaran"=> empty($item->GetDokumenAnggaran())? null:Utility::loadAsset("dokumen_anggaran/".$item->GetDokumenAnggaran()),
                                        "verifikator_nidn"=>$item->GetVerifikasi()?->GetNidn(),
                                        "verifikator_nip"=>$item->GetVerifikasi()?->GetNip(),
                                        "status"=> $item->GetStatus(),
                                    ],
                                };
                            });
        
        
        return DataTables::of($listSPPD)
        ->addIndexColumn()
        ->addColumn('action', function ($row) use($level,$nidn,$nip,$verifikasi){
            $render = '';
            // if(in_array($level,['dosen','pegawai']) && !$verifikasi){
            //     if(empty($row->status) || in_array($row->status, ['menunggu','tolak atasan','tolak sdm'])){
            //         $render = '<div class="row">
            //         <a href="'.route('sppd.edit',['id'=>$row->id]).'" class="col-6 btn btn-warning"><i class="bi bi-pencil-square"></i></a>
            //         <a href="'.route('sppd.delete',['id'=>$row->id]).'" class="mx-2 col-6 btn btn-danger"><i class="bi bi-trash"></i></a>
            //         </div>
            //         ';
            //     } 
            //     // else {
            //     //     $render = '<a href="#" class="btn btn-info btn-download-pdf"><i class="bi bi-file-earmark-pdf"></i></a>';
            //     // }
            // }else if( 
            //     (
            //         (!is_null($row->verifikator_nidn) && $row->verifikator_nidn==$nidn) ||
            //         (!is_null($row->verifikator_nip) && $row->verifikator_nip==$nip)
            //     ) || 
            //     in_array($row->status, ["menunggu","menunggu verifikasi sdm"])){
            //     $render = '
            //         <a href="'.route('sppd.approval',['id'=>$row->id,'type'=>($level=="sdm"? 'terima sdm':'menunggu verifikasi sdm')]).'" class="btn btn-success"><i class="bi bi-check-lg"></i></a>
            //         <a href="#" class="mx-2 btn btn-danger btn-reject"><i class="bi bi-x-lg"></i></a>
            //     ';
            //     // if($row->status=="terima"){
            //     // $render .= '<a href="#" class="btn btn-info btn-download-pdf"><i class="bi bi-file-earmark-pdf"></i></a>';
            //     // }
            // }

            if(in_array($level,['dosen','pegawai']) && !$verifikasi){
                if(in_array($row->status, ['menunggu','tolak warek','tolak sdm']) && (($row->nidn==$nidn && !empty($nidn)) || ($row->nip==$nip && !empty($nip))) ){
                    $render = '<div class="row">
                    <a href="'.route('sppd.edit',['id'=>$row->id]).'" class="col-6 btn btn-warning"><i class="bi bi-pencil-square"></i></a>
                    <a href="'.route('sppd.delete',['id'=>$row->id]).'" class="mx-2 col-6 btn btn-danger"><i class="bi bi-trash"></i></a>
                    </div>
                    ';
                } 
                else if(in_array($row->status, ['tolak sdm','menunggu verifikasi sdm'])){
                    $render = '<a href="#" class="ml-2 btn btn-secondary btn-download-anggaran"><i class="bi bi-wallet2"></i></a>';
                }
                else if($row->status=="terima sdm"){
                    $render = '
                        <a href="#" class="ml-2 btn btn-secondary btn-download-anggaran"><i class="bi bi-wallet2"></i></a>
                        <a href="#" class="btn btn-info btn-download-pengajuan-pdf"><i class="bi bi-file-earmark-pdf"></i></a>
                        <a href="'.route('sppd.laporan',['id'=>$row->id]).'" class="btn btn-success"><i class="bi bi-file-earmark-plus-fill"></i></a>';
                }
            }
            else if($verifikasi){
                $level = $level=="dosen" && $verifikasi? 'warek':$level??'-';

                if($row->status=='menunggu' && $level=="warek"){
                    $render = '
                    <a href="#" class="btn btn-success btn-approve"><i class="bi bi-check-lg"></i></a>
                    <a href="#" class="ml-2 btn btn-danger btn-reject"><i class="bi bi-x-lg"></i></a>';
                }
                else if($row->status=='menunggu verifikasi sdm' && $level=="sdm"){
                    $render = '
                    <a href="'.route('sppd.approval',['id'=>$row->id,'type'=>$level]).'" class="btn btn-success"><i class="bi bi-check-lg"></i></a>
                    <a href="#" class="ml-2 btn btn-danger btn-reject"><i class="bi bi-x-lg"></i></a>';
                }
                else if(in_array($row->status, ['menunggu verifikasi sdm'])){
                    $render = '<a href="#" class="ml-2 btn btn-secondary btn-download-anggaran"><i class="bi bi-wallet2"></i></a>';
                }
                else if($row->status=="terima sdm"){
                    $render = '
                        <a href="#" class="ml-2 btn btn-secondary btn-download-anggaran"><i class="bi bi-wallet2"></i></a>
                        <a href="#" class="btn btn-info btn-download-pengajuan-pdf"><i class="bi bi-file-earmark-pdf"></i></a>';
                }
            }
            
            // dump($level,$nidn,$nip,$verifikasi,$render);
            return $render;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
