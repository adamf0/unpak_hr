<?php

namespace Architecture\External\Datatable\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\MasterKalendar\List\GetAllMasterKalendarQuery;
use Architecture\Domain\Enum\FormatDate;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;
use Architecture\Shared\Facades\Utility;

class DatatableMasterKalendarController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function index(Request $request){
        $q = new GetAllMasterKalendarQuery();
        // $q->SetOffset($request->get('start')??null)->SetLimit($request->get('length')??null);
        
        $listMasterKalendar = $this->queryBus->ask($q);
        $listMasterKalendar = $listMasterKalendar->map(fn($item)=>(object)[
            "id"=>$item->GetId(),
            "tanggal_mulai"=>$item->GetTanggalMulai()->toFormat(FormatDate::LDFY),
            "tanggal_akhir"=>$item->GetTanggalAkhir()?->toFormat(FormatDate::LDFY),
            "keterangan"=>$item->GetKeterangan()
        ]);
        
        return DataTables::of($listMasterKalendar)
        ->addIndexColumn()
        ->addColumn('tanggal_awal_akhir', function ($row) {
            $render = $row->tanggal_akhir==null || $row->tanggal_akhir==$row->tanggal_mulai? $row->tanggal_mulai : "{$row->tanggal_mulai} - {$row->tanggal_akhir}";
            return $render;
        })
        ->addColumn('action', function ($row) {
            $render = '<div class="row gap-2">
            <a href="'.route('master_kalendar.edit',['id'=>$row->id]).'" class="btn btn-warning"><i class="bi bi-pencil-square"></i></a>
            <a href="'.route('master_kalendar.delete',['id'=>$row->id]).'" class="btn btn-danger"><i class="bi bi-trash"></i></a>
            </div>';
            return $render;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
