<?php

namespace Architecture\External\Persistance\Queries\VideoKegiatan;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\VideoKegiatan\List\GetAllVideoKegiatanQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\VideoKegiatanEntitas;
use Architecture\External\Persistance\ORM\VideoKegiatan as VideoKegiatanModel;
use Architecture\Shared\TypeData;
use Illuminate\Database\Eloquent\Collection;

class GetAllVideoKegiatanQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllVideoKegiatanQuery $query)
    {
        $datas = VideoKegiatanModel::get();

        if($query->getOption()==TypeData::Default) return new Collection($datas);

        return $datas->transform(fn($data)=> Creator::buildVideoKegiatan(VideoKegiatanEntitas::make(
            $data->id,
            $data->nama,
            $data->nilai,
        )));
    }
}