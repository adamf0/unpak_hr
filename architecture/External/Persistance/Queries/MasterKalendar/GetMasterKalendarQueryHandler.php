<?php

namespace Architecture\External\Persistance\Queries\MasterKalendar;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\MasterKalendar\FirstData\GetMasterKalendarQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\MasterKalendarEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\MasterKalendar as ModelMasterKalendar;
use Architecture\Shared\TypeData;
use Illuminate\Support\Facades\DB;

class GetMasterKalendarQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetMasterKalendarQuery $query)
    {
        $data = ModelMasterKalendar::findOrFail($query->GetId());
        if($query->getOption()==TypeData::Default) return $data;

        return Creator::buildMasterKalendar(MasterKalendarEntitas::make(
            $data->id,
            $data->tanggal_mulai==null? null:new Date($data->tanggal_mulai),
            $data->tanggal_berakhir==null? null:new Date($data->tanggal_berakhir),
            $data?->keterangan
        ));
    }
}