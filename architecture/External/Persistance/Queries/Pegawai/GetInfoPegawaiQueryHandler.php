<?php

namespace Architecture\External\Persistance\Queries\Dosen;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Pegawai\FirstData\GetInfoPegawaiQuery;
use Architecture\External\Persistance\ORM\NPribadi;
use Architecture\Shared\TypeData;

class GetInfoPegawaiQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetInfoPegawaiQuery $query)
    {
        $data = NPribadi::select('id_n_pribadi','nip','nama','status_pegawai')
                    ->where('nip',$query->GetNIP())
                    ->first();
        if($query->getOption()==TypeData::Default) return $data;
    }
}