<?php

namespace Architecture\External\Persistance\Queries\Pegawai;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Pegawai\List\GetAllPegawaiQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\PegawaiEntitas;
use Architecture\External\Persistance\ORM\NPribadi;
use Architecture\Shared\TypeData;

class GetAllPegawaiQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllPegawaiQuery $query)
    {
        $datas = NPribadi::select('id_n_pribadi','nip','nama','status_pegawai')->get();

        if($query->getOption()==TypeData::Default) return $datas;

        return $datas->transform(fn($data)=> Creator::buildPegawai(PegawaiEntitas::make(
            null,
            $data->nip,
            $data->nama,
            "unit",
        )) );
    }
}