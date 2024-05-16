<?php

namespace Architecture\External\Persistance\Queries\JenisSPPD;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\JenisSPPD\FirstData\GetJenisSPPDQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\JenisSPPDEntitas;
use Architecture\External\Persistance\ORM\JenisSPPD as JenisSPPDModel;
use Architecture\Shared\TypeData;

class GetJenisSPPDQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetJenisSPPDQuery $query)
    {
        $data = JenisSPPDModel::where('id',$query->GetId())->first();

        if($query->getOption()==TypeData::Default) return $data;

        return Creator::buildJenisSPPD(JenisSPPDEntitas::make(
            $data->id,
            $data->nama,
        ));
    }
}