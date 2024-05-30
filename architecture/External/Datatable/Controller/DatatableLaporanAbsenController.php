<?php

namespace Architecture\External\Datatable\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\LaporanAbsen\List\GetAllLaporanAbsenQuery;
use Architecture\Shared\TypeData;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;

class DatatableLaporanAbsenController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function index(Request $request){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time','-1');        
        $laporan = $this->queryBus->ask(new GetAllLaporanAbsenQuery(null,null,null,null,TypeData::Default));

        $table = DataTables::of(isset($laporan["list_data"])? $laporan["list_data"]:[])
        ->addIndexColumn()
        ->make(true);

        return $table;
    }
}
