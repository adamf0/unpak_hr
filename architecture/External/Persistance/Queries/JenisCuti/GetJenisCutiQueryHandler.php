<?php

namespace Architecture\External\Persistance\Queries\JenisCuti;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\JenisCuti\FirstData\GetJenisCutiQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\JenisCutiEntitas;
use Architecture\External\Persistance\ORM\JenisCuti as JenisCutiModel;
use Architecture\Shared\TypeData;

class GetJenisCutiQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetJenisCutiQuery $query)
    {
        $data = JenisCutiModel::where('id',$query->GetId())->first();

        if($query->getOption()==TypeData::Default) return $data;

        return Creator::buildJenisCuti(JenisCutiEntitas::make(
            $data->id,
            $data->nama,
            $data->min,
            $data->max,
            $data->dokumen,
            $data->kondisi,
        ));
    }
}