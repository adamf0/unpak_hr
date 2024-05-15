<?php

namespace Architecture\External\Persistance\Queries\Dosen;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Dosen\GetAllDosenQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\DosenEntitasTanpaInfo;
use Architecture\External\Persistance\ORM\Dosen as modelDosen;
use Architecture\Shared\TypeData;

class GetAllDosenQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllDosenQuery $query)
    {
        $datas = modelDosen::get();
        if($query->getOption()==TypeData::Default) return $datas;

        return $datas->transForm(fn($data)=> Creator::buildDosen(DosenEntitasTanpaInfo::make(
            $data->NIDN,
            $data->nama_dosen
        )) );
    }
}