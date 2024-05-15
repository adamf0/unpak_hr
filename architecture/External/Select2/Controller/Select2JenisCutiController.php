<?php

namespace Architecture\External\Select2\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\JenisCuti\List\GetAllJenisCutiQuery;

class Select2JenisCutiController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function List(){
        $list = $this->queryBus->ask(new GetAllJenisCutiQuery());
        $list = $list->map(fn($item)=>[
            "id"=>$item->GetId(),
            "text"=>$item->GetNama(),
            "min"=>$item->GetMin(),
            "max"=>$item->GetMax(),
            "dokumen"=>$item->GetDokumen(),
            "kondisi"=>json_decode($item->GetKondisi(), true)
        ]);

        return response()->json($list);
    }
}
