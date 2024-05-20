<?php

namespace Architecture\External\Persistance\Queries\SPPD;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\SPPD\FirstData\GetSPPDQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\JenisSPPDEntitas;
use Architecture\Domain\Entity\SPPDEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\SPPD as SPPDModel;
use Architecture\Shared\TypeData;

class GetSPPDQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetSPPDQuery $query)
    {
        $data = SPPDModel::with(['JenisSPPD'])->where('id',$query->GetId())->first();

        if($query->getOption()==TypeData::Default) return $data;

        return Creator::buildSPPD(SPPDEntitas::make(
            $data->id,
            $data->nidn,
            $data->nip,
            Creator::buildJenisSPPD(JenisSPPDEntitas::make(
                $data->JenisSPPD?->id,
                $data->JenisSPPD?->nama,
            )),
            new Date($data->tanggal_berangkat),
            new Date($data->tanggal_kembali),
            $data->tujuan,
            $data->keterangan,
            $data->status,
            $data->catatan,
        ));
    }
}