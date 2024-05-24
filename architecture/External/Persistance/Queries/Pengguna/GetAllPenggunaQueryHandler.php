<?php

namespace Architecture\External\Persistance\Queries\Pengguna;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Pengguna\List\GetAllPenggunaQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\PenggunaEntitasTanpaStatus;
use Architecture\External\Persistance\ORM\User as PenggunaModel;
use Architecture\Shared\TypeData;
use Illuminate\Database\Eloquent\Collection;

class GetAllPenggunaQueryHandler extends Query
{
    public function __construct() {}

    public function handle(GetAllPenggunaQuery $query)
    {
        $datas = PenggunaModel::get();

        if($query->getOption()==TypeData::Default) return new Collection($datas);

        return $datas->transform(fn($data)=> Creator::buildPengguna(PenggunaEntitasTanpaStatus::make(
            $data->id,
            $data->username,
            $data->password,
            $data->name,
            null,
            $data->level
        )) );
    }
}