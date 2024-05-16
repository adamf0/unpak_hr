<?php

namespace Architecture\External\Select2\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\JenisSPPD\List\GetAllJenisSPPDQuery;

class Select2JenisSPPDController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function List(){
        $list = $this->queryBus->ask(new GetAllJenisSPPDQuery());
        $list = $list->map(fn($item)=>[
            "id"=>$item->GetId(),
            "text"=>$item->GetNama(),
        ]);

        return response()->json($list);
    }
}
