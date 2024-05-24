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
        $q = new GetAllSPPDQuery($nidn,$nip);
        // $q->SetOffset($request->get('start')??null)->SetLimit($request->get('length')??null);
        
        $listSPPD = $this->queryBus->ask($q);
        $listSPPD = $listSPPD->map(fn($item)=>(object)[
            "id"=>$item->GetId(),
            "nidn"=> $item->GetNIDN(),
            "nip"=> $item->GetNIP(),
            "jenis_sppd"=> $item->GetJenisSPPD()?->GetNama(),
            "tanggal_berangkat"=> $item->GetTanggalBerangkat()->toFormat(FormatDate::LDFY),
            "tanggal_kembali"=> $item->GetTanggalKembali()->toFormat(FormatDate::LDFY),
            "tujuan"=> $item->GetTujuan(),
            "keterangan"=> $item->GetKeterangan(),
            "catatan"=> $item->GetCatatan(),
            "status"=> $item->GetStatus(),
        ]);
        
        return DataTables::of($listSPPD)
        ->addIndexColumn()
        ->addColumn('action', function ($row) use($level){
            $render = '';
            if(in_array($level,['dosen','pegawai'])){
                if(in_array($row->status, ['menunggu','tolak'])){
                    $render = '<div class="row">
                    <a href="'.route('sppd.edit',['id'=>$row->id]).'" class="col-6 btn btn-warning"><i class="bi bi-pencil-square"></i></a>
                    <a href="'.route('sppd.delete',['id'=>$row->id]).'" class="mx-2 col-6 btn btn-danger"><i class="bi bi-trash"></i></a>
                    </div>
                    ';
                } else {
                    $render = '<a href="#" class="btn btn-info btn-download-pdf"><i class="bi bi-file-earmark-pdf"></i></a>';
                }
            }
            else if($level=="sdm"){
                $render = '
                    <a href="'.route('sppd.approval',['id'=>$row->id]).'" class="btn btn-success"><i class="bi bi-check-lg"></i></a>
                    <a href="#" class="mx-2 btn btn-danger btn-reject"><i class="bi bi-x-lg"></i></a>
                ';
                if($row->status=="terima"){
                $render .= '<a href="#" class="btn btn-info btn-download-pdf"><i class="bi bi-file-earmark-pdf"></i></a>';
                }
            }
            
            return $render;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
