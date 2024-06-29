<?php

namespace Architecture\External\Select2\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Presensi\List\GetAllPresensiQuery;
use Architecture\Domain\Enum\FormatDate;
use Architecture\Domain\ValueObject\Date;
use Architecture\Shared\TypeData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\DataTables as DataTables;

class Select2FilterMonitorController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function List(Request $request){
        $datas = $this->queryBus->ask(new GetAllPresensiQuery(null,null,null,TypeData::Default));
        
        // $datas = Cache::remember("filter-monitor", 5*60, function () use($datas){
        //     return ;
        // });
        $datas = $datas->transform(function($item){
            return [
                "id"=>$item->unit_kerja,
                "text"=>$item->unit_kerja,
            ];   
        });

        return response()->json($datas);
    }
}
