<?php

namespace Architecture\External\Persistance\Queries\Presensi;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Presensi\FirstData\GetPresensiQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\PresensiEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\Absensi as ModelAbsensi;
use Architecture\Shared\TypeData;

class GetPresensiQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetPresensiQuery $query)
    {
        $data = ModelAbsensi::where('id',$query->GetId())->first();

        if($query->getOption()==TypeData::Default) return $data;

        return Creator::buildPresensi(PresensiEntitas::make(
            $data->id,
            $data->nidn,
            $data->nip,
            New Date($data->tanggal),
            $data->absen_masuk==null? null:new Date($data->absen_masuk),
            $data->absen_keluar==null? null:new Date($data->absen_keluar),
            $data->catatan_telat,
            $data->catatan_keluar,
            $data->otomatis_keluar,
        ));
    }
}