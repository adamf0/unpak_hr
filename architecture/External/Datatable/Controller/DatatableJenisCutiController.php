<?php

namespace Architecture\External\Datatable\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\JenisCuti\List\GetAllJenisCutiQuery;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;

class DatatableJenisCutiController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function index(Request $request){
        $q = new GetAllJenisCutiQuery();
        // $q->SetOffset($request->get('start')??null)->SetLimit($request->get('length')??null);
        
        $listJenisCuti = $this->queryBus->ask($q);
        $listJenisCuti = $listJenisCuti->map(fn($item)=>(object)["id"=>$item->GetId(),"nama"=>$item->GetNama()]);
        
        return DataTables::of($listJenisCuti)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
            $actionBtn = '<div class="row gap-2">
            <a href="'.route('jenis_cuti.edit',['id'=>$row->id]).'" class="btn btn-warning"><i class="bi bi-pencil-square"></i></a>
            <a href="'.route('jenis_cuti.delete',['id'=>$row->id]).'" class="btn btn-danger"><i class="bi bi-trash"></i></a>
            </div>';
            return $actionBtn;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
