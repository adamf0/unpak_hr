<?php

namespace Architecture\External\Persistance\Queries\Izin;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Izin\List\GetAllIzinQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\IzinEntitas;
use Architecture\Domain\Entity\JenisIzinEntitas;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\Izin as IzinModel;
use Architecture\Shared\TypeData;
use Illuminate\Database\Eloquent\Collection;

class GetAllIzinQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllIzinQuery $query)
    {
        $datas = IzinModel::with(['JenisIzin']);
        if(!is_null($query->GetNIDN())){
            $datas = $datas->where('nidn',$query->GetNIDN());
        }
        if(!is_null($query->GetNIP())){
            $datas = $datas->where('nip',$query->GetNIP());
        }
        $datas = $datas->get();

        if($query->getOption()==TypeData::Default) return new Collection($datas);

        return $datas->transform(fn($data)=> Creator::buildIzin(IzinEntitas::make(
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
        )) );
    }
}