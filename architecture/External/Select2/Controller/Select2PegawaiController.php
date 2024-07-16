<?php

namespace Architecture\External\Select2\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Pegawai\List\GetAllPegawaiQuery;

class Select2PegawaiController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function List(){
        $list_pegawai = $this->queryBus->ask(new GetAllPegawaiQuery());

        $listPegawai = $list_pegawai->reduce(function ($carry, $item){
            $carry[] = [
                "id"=>$item->GetNip(),
                "text"=>sprintf("%s - %s (%s)", $item->GetNama(), $item->GetNip(), $item?->GetUnit()??""),
            ];
            return $carry;
        }, []);

        return response()->json($listPegawai);
    }
}
