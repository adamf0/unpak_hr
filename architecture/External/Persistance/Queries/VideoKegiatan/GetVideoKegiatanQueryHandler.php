<?php

namespace Architecture\External\Persistance\Queries\VideoKegiatan;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\VideoKegiatan\FirstData\GetVideoKegiatanQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\VideoKegiatanEntitas;
use Architecture\External\Persistance\ORM\VideoKegiatan as VideoKegiatanModel;
use Architecture\Shared\TypeData;

class GetVideoKegiatanQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetVideoKegiatanQuery $query)
    {
        $data = VideoKegiatanModel::where('id',$query->GetId())->first();

        if($query->getOption()==TypeData::Default) return $data;

        return Creator::buildVideoKegiatan(VideoKegiatanEntitas::make(
            $data->id,
            $data->nama,
            $data->nilai,
        ));
    }
}