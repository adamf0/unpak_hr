<?php

namespace Architecture\External\Persistance\Queries\Presensi;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Presensi\List\GetAllPresensiQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\PresensiEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\Absensi as ModelAbsensi;
use Architecture\Shared\TypeData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GetAllPresensiQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllPresensiQuery $query)
    {
        $datas = DB::table('absen');
        if(!empty($query->GetNIDN())){
            $datas = $datas->where('nidn',$query->GetNIDN());
        }
        if(!empty($query->GetNIP())){
            $datas = $datas->where('nip',$query->GetNIP());
        }
        if(!empty($query->GetTahun())){
            $datas = $datas->where(DB::raw('YEAR(tanggal)'),$query->GetTahun());
        }
        $datas = $datas->get();

        if($query->getOption()==TypeData::Default) return new Collection($datas);

        return $datas->transform(fn($data)=> Creator::buildPresensi(PresensiEntitas::make(
            $data->id,
            $data->nidn,
            $data->nip,
            New Date($data->tanggal),
            $data->absen_masuk==null? null:new Date($data->absen_masuk),
            $data->absen_keluar==null? null:new Date($data->absen_keluar),
            $data->catatan_telat,
            $data->catatan_keluar,
            $data->otomatis_keluar,
        )) );
    }
}