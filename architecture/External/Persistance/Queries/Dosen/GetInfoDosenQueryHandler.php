<?php

namespace Architecture\External\Persistance\Queries\Dosen;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Dosen\GetInfoDosenQuery;
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
    }
}