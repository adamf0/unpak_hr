<?php

namespace Architecture\External\Persistance\Queries\MasterKalendar;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\MasterKalendar\List\GetAllMasterKalendarInRangeQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\MasterKalendarEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\MasterKalendar as ModelMasterKalendar;
use Architecture\Shared\TypeData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GetAllMasterKalendarInRangeQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllMasterKalendarInRangeQuery $query)
    {
        $dates = $query->GetDateRange();
        if(count($query->GetDateRange())==2){
            $datas = ModelMasterKalendar::where(function($q) use($dates){
                $q->where('tanggal_berangkat','>=',$dates[0])->where('tanggal_kembali','<=',$dates[0]);
            }); 
            $datas = $datas->orWhere(function($q) use($dates){
                $q->where('tanggal_berangkat','>=',$dates[1])->where('tanggal_kembali','<=',$dates[1]);
            }); 
        } else if(count($query->GetDateRange())==1){
            $datas = ModelMasterKalendar::where('tanggal_berangkat','>=',$dates[0])->where('tanggal_kembali','<=',$dates[0]);
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