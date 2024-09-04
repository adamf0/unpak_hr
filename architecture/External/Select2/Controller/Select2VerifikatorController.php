<?php

namespace Architecture\External\Select2\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Pegawai\List\GetAllPegawaiV2Query;
use Illuminate\Http\Request;

class Select2VerifikatorController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {
    }

    public function List(Request $request)
    {
        $struktural = $request->has('struktural') ? $request->query('struktural') : null;

        $list_verifikator = $this->queryBus->ask(new GetAllPegawaiV2Query($struktural));
        $listVerifikator = $list_verifikator->reduce(function ($carry, $item){
            $carry[] = [
                "id"=>$item->GetNIDN(),
                "text"=>!empty($item->GetStruktural())? $item->GetNama()." - ".$item->GetNIDN()." - ".$item->GetStruktural():$item->GetNama()." - ".$item->GetNIDN(),
                // "fakultas_unit"=>,
            ];
            return $carry;
        }, []);
        
        return response()->json($listVerifikator);
    }
}
