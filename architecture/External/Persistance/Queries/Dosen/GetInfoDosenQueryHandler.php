<?php

namespace Architecture\External\Persistance\Queries\Dosen;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Dosen\GetInfoDosenQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\DosenEntitas;
use Architecture\Domain\Entity\FakultasEntitas;
use Architecture\Domain\Entity\ProdiEntitas;
use Architecture\External\Persistance\ORM\Dosen as DosenModel;
use Architecture\Shared\TypeData;

class GetInfoDosenQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetInfoDosenQuery $query)
    {
        $data = DosenModel::with([
            'Fakultas' => function ($q) {
                $q->select('kode_fakultas', 'nama_fakultas');
            },
            'Prodi' => function ($q) {
                $q->select('kode_prodi', 'nama_prodi');
            },
        ])
        ->where('NIDN',$query->getNIDN())
        ->first();
        if($query->getOption()==TypeData::Default) return $data;

        // return Creator::buildDosen(DosenEntitas::make(
        //     $data->NIDN,
        //     $data->nama_dosen,
        //     Creator::buildFakultas(FakultasEntitas::make(
        //         $data->Fakultas?->kode_fakultas,
        //         $data->Fakultas?->nama_fakultas
        //     )),
        //     $data->kode_jurusan,
        //     Creator::buildProdi(ProdiEntitas::make(
        //         $data->Prodi?->kode_prodi,
        //         $data->Prodi?->nama_prodi
        //     )),
        // ));
    }
}