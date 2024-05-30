<?php

namespace Architecture\External\Persistance\Queries\Dosen;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Dosen\GetAllDosenQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\DosenEntitas;
use Architecture\Domain\Entity\FakultasEntitas;
use Architecture\Domain\Entity\ProdiEntitas;
use Architecture\External\Persistance\ORM\Dosen as modelDosen;
use Architecture\Shared\TypeData;

class GetAllDosenQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllDosenQuery $query)
    {
        $datas = modelDosen::with(['Fakultas','Prodi'])->get();
        if($query->getOption()==TypeData::Default) return $datas;

        return $datas->transForm(fn($data)=> Creator::buildDosen(DosenEntitas::make(
            $data->NIDN,
            $data->nama_dosen,
            !is_null($data->Fakultas)? Creator::buildFakultas(FakultasEntitas::make(
                $data->Fakultas?->kode_fakultas,
                $data->Fakultas?->nama_fakultas,
            )):null,
            !is_null($data->Prodi)? Creator::buildProdi(ProdiEntitas::make(
                $data->Prodi?->kode_prodi,
                $data->Prodi?->nama_prodi,
            )):null,
        )) );
    }
}