<?php

namespace Architecture\External\Select2\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\JenisIzin\List\GetAllJenisIzinQuery;

class Select2JenisIzinController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function List(){
        $list = $this->queryBus->ask(new GetAllJenisIzinQuery());
        $list = $list->map(fn($item)=>[
            "id"=>$item->GetId(),
            "text"=>$item->GetNama(),
        ]);

        return response()->json($list);
    }
}
