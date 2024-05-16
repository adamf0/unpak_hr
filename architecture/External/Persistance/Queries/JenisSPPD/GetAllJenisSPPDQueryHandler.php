<?php

namespace Architecture\External\Persistance\Queries\JenisSPPD;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\JenisSPPD\List\GetAllJenisSPPDQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\JenisSPPDEntitas;
use Architecture\External\Persistance\ORM\JenisSPPD as JenisSPPDModel;
use Architecture\Shared\TypeData;
use Illuminate\Database\Eloquent\Collection;

class GetAllJenisSPPDQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllJenisSPPDQuery $query)
    {
        $datas = JenisSPPDModel::get();

        if($query->getOption()==TypeData::Default) return new Collection($datas);

        return $datas->transform(fn($data)=> Creator::buildJenisSPPD(JenisSPPDEntitas::make(
            $data->id,
            $data->nama,
        )) );
    }
}