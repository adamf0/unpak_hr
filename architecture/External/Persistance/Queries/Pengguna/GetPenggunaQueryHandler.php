<?php

namespace Architecture\External\Persistance\Queries\Pengguna;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Pengguna\FirstData\GetPenggunaQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\PenggunaEntitasTanpaStatus;
use Architecture\External\Persistance\ORM\User as PenggunaModel;
use Architecture\Shared\TypeData;

class GetPenggunaQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetPenggunaQuery $query)
    {
        $data = PenggunaModel::where('id',$query->GetId())->first();

        if($query->getOption()==TypeData::Default) return $data;

        return Creator::buildPengguna(PenggunaEntitasTanpaStatus::make(
            $data->id,
            $data->username,
            $data->password,
            $data->name,
            null,
            $data->level
        ));
    }
}