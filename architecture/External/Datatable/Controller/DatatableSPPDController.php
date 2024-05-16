<?php

namespace Architecture\External\Datatable\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\SPPD\List\GetAllSPPDQuery;
use Architecture\Domain\Enum\FormatDate;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;

class DatatableSPPDController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function index(Request $request){
        $q = new GetAllSPPDQuery();
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
            "status"=> $item->GetStatus(),
        ]);
        
        return DataTables::of($listSPPD)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
            $actionBtn = '
            <a href="'.route('sppd.edit',['id'=>$row->id]).'" class="btn btn-warning"><i class="bi bi-pencil-square"></i></a>
            <a href="'.route('sppd.delete',['id'=>$row->id]).'" class="btn btn-danger"><i class="bi bi-trash"></i></a>
            ';
            return $actionBtn;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
