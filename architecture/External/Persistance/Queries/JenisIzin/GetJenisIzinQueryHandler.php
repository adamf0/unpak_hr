<?php

namespace Architecture\External\Persistance\Queries\JenisIzin;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\JenisIzin\FirstData\GetJenisIzinQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\JenisIzinEntitas;
use Architecture\External\Persistance\ORM\JenisIzin as JenisIzinModel;
use Architecture\Shared\TypeData;

class GetJenisIzinQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetJenisIzinQuery $query)
    {
        $data = JenisIzinModel::where('id',$query->GetId())->first();

        if($query->getOption()==TypeData::Default) return $data;

        return Creator::buildJenisIzin(JenisIzinEntitas::make(
            $data->id,
            $data->nama,
        ));
    }
}