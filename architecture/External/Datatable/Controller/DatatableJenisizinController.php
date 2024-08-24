<?php

namespace Architecture\External\Datatable\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\JenisIzin\List\GetAllJenisIzinQuery;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;

class DatatableJenisIzinController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function index(Request $request){
        $q = new GetAllJenisIzinQuery();
        // $q->SetOffset($request->get('start')??null)->SetLimit($request->get('length')??null);
        
        $listJenisIzin = $this->queryBus->ask($q);
        $listJenisIzin = $listJenisIzin->map(fn($item)=>(object)["id"=>$item->GetId(),"nama"=>$item->GetNama()]);
        
        return DataTables::of($listJenisIzin)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
            $actionBtn = '<div class="row gap-2">
            <a href="'.route('jenis_izin.edit',['id'=>$row->id]).'" class="btn btn-warning"><i class="bi bi-pencil-square"></i></a>
            <a href="'.route('jenis_izin.delete',['id'=>$row->id]).'" class="btn btn-danger"><i class="bi bi-trash"></i></a>
            </div>';
            return $actionBtn;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
