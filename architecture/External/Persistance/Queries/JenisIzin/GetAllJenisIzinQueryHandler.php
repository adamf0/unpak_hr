<?php

namespace Architecture\External\Persistance\Queries\JenisIzin;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\JenisIzin\List\GetAllJenisIzinQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\JenisIzinEntitas;
use Architecture\External\Persistance\ORM\JenisIzin as JenisIzinModel;
use Architecture\Shared\TypeData;
use Illuminate\Database\Eloquent\Collection;

class GetAllJenisIzinQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllJenisIzinQuery $query)
    {
        $datas = JenisIzinModel::get();

        if($query->getOption()==TypeData::Default) return new Collection($datas);

        return $datas->transform(fn($data)=> Creator::buildJenisIzin(JenisIzinEntitas::make(
            $data->id,
            $data->nama,
        )) );
    }
}