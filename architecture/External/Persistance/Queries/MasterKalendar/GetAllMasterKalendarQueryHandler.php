<?php

namespace Architecture\External\Persistance\Queries\MasterKalendar;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\MasterKalendar\List\GetAllMasterKalendarQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\MasterKalendarEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\MasterKalendar as ModelMasterKalendar;
use Architecture\Shared\TypeData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GetAllMasterKalendarQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllMasterKalendarQuery $query)
    {
        if(!is_null($query->GetTahun())){
            $datas = ModelMasterKalendar::where(DB::raw('YEAR(tanggal_mulai)'),'>=',$query->GetTahun())->where(DB::raw('YEAR(tanggal_berakhir)'),'<=',$query->GetTahun())->get();
        } else{
            $datas = ModelMasterKalendar::get();
        }

        if($query->getOption()==TypeData::Default) return new Collection($datas);

        return $datas->transform(fn($data)=> Creator::buildMasterKalendar(MasterKalendarEntitas::make(
            $data->id,
            $data->tanggal_mulai==null? null:new Date($data->tanggal_mulai),
            $data->tanggal_berakhir==null? null:new Date($data->tanggal_berakhir),
            $data?->keterangan
        )) );
    }
}