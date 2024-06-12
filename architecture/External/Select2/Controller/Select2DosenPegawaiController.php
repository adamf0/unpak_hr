<?php

namespace Architecture\External\Select2\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Dosen\GetAllDosenQuery;
use Architecture\Application\Pegawai\List\GetAllPegawaiQuery;

class Select2DosenPegawaiController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function List(){
        $list_dosen = $this->queryBus->ask(new GetAllDosenQuery());
        $list_pegawai = $this->queryBus->ask(new GetAllPegawaiQuery());

        $listPegawai = $list_pegawai->reduce(function ($carry, $item){
            $carry[] = [
                "id"=>$item->GetNip(),
                "text"=>sprintf("%s - %s (%s)", $item->GetNama(), $item->GetNip(), (!empty($item->GetUnit())? $item->GetUnit():null)),
                "type"=>"pegawai",
            ];
            return $carry;
        }, []);
        $listDosen = $list_dosen->reduce(function ($carry, $item){
            $carry[] = [
                "id"=>$item->GetNidn(),
                "text"=>$item->GetNama()." - ".(!empty($item->GetFakultas())? $item->GetFakultas()?->GetNamaFakultas():null),
                "type"=>"dosen",
            ];
            return $carry;
        }, []);

        return response()->json(array_merge($listDosen,$listPegawai));
    }
}
