<?php

namespace Architecture\External\Datatable\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Pengguna\List\GetAllPenggunaQuery;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;

class DatatablePenggunaController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function index(Request $request){
        $q = new GetAllPenggunaQuery();
        // $q->SetOffset($request->get('start')??null)->SetLimit($request->get('length')??null);
        
        $listPengguna = $this->queryBus->ask($q);
        $listPengguna = $listPengguna->map(fn($item)=>(object)[
            "id"=>$item->GetId(),
            "nama"=>$item->GetName(),
            "username"=>$item->GetUsername(),
            "level"=>$item->GetLevel()
        ]);
        
        return DataTables::of($listPengguna)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
            $actionBtn = '
            <a href="'.route('pengguna.edit',['id'=>$row->id]).'" class="btn btn-warning"><i class="bi bi-pencil-square"></i></a>
            <a href="'.route('pengguna.delete',['id'=>$row->id]).'" class="btn btn-danger"><i class="bi bi-trash"></i></a>
            ';
            return $actionBtn;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
