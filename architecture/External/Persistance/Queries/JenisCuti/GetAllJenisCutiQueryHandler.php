<?php

namespace Architecture\External\Persistance\Queries\JenisCuti;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\JenisCuti\List\GetAllJenisCutiQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\JenisCutiEntitas;
use Architecture\External\Persistance\ORM\JenisCuti as JenisCutiModel;
use Architecture\Shared\TypeData;
use Illuminate\Database\Eloquent\Collection;

class GetAllJenisCutiQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllJenisCutiQuery $query)
    {
        $datas = JenisCutiModel::get();

        if($query->getOption()==TypeData::Default) return new Collection($datas);

        return $datas->transform(fn($data)=> Creator::buildJenisCuti(JenisCutiEntitas::make(
            $data->id,
            $data->nama,
            $data->min,
            $data->max,
            $data->dokumen,
            $data->kondisi,
        )) );
    }
}