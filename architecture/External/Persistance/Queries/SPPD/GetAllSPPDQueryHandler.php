<?php

namespace Architecture\External\Persistance\Queries\SPPD;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\SPPD\List\GetAllSPPDQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\JenisSPPDEntitas;
use Architecture\Domain\Entity\SPPDEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\SPPD as SPPDModel;
use Architecture\Shared\TypeData;
use Illuminate\Database\Eloquent\Collection;

class GetAllSPPDQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllSPPDQuery $query)
    {
        $datas = SPPDModel::with(['JenisSPPD']);
        if(!is_null($query->GetNIDN())){
            $datas = $datas->where('nidn',$query->GetNIDN());
        }
        if(!is_null($query->GetNIP())){
            $datas = $datas->where('nip',$query->GetNIP());
        }
        $datas = $datas->get();

        if($query->getOption()==TypeData::Default) return new Collection($datas);

        return $datas->transform(fn($data)=> Creator::buildSPPD(SPPDEntitas::make(
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
        )) );
    }
}