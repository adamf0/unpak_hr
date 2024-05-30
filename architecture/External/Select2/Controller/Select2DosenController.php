<?php

namespace Architecture\External\Select2\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Dosen\GetAllDosenQuery;

class Select2DosenController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function List(){
        $list_dosen = $this->queryBus->ask(new GetAllDosenQuery());

        $listDosen = $list_dosen->reduce(function ($carry, $item){
            $carry[] = [
                "id"=>$item->GetNidn(),
                "text"=>$item->GetNama()." - ".(!empty($item->GetFakultas())? $item->GetFakultas()?->GetNamaFakultas():null),
                // "fakultas_unit"=>,
            ];
            return $carry;
        }, []);

        return response()->json($listDosen);
    }
}
