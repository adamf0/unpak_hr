<?php

namespace Architecture\External\Persistance\Queries\Izin;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Izin\FirstData\GetIzinQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\IzinEntitas;
use Architecture\Domain\Entity\JenisIzinEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\Izin as IzinModel;
use Architecture\Shared\TypeData;

class GetIzinQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetIzinQuery $query)
    {
        $data = IzinModel::with(['JenisIzin'])->where('id',$query->GetId())->first();

        if($query->getOption()==TypeData::Default) return $data;

        return Creator::buildIzin(IzinEntitas::make(
            $data->id,
            $data->nidn,
            $data->nip,
            new Date($data->tanggal_pengajuan),
            $data->tujuan,
            Creator::buildJenisIzin(JenisIzinEntitas::make(
                $data->JenisIzin?->id,
                $data->JenisIzin?->nama,
            )),
            $data->dokumen,
            $data->catatan,
            $data->status,
        ));
    }
}