<?php

namespace Architecture\External\Persistance\Queries\Dosen;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Dosen\CheckDosenByNIDNQuery;
use Architecture\External\Persistance\ORM\Dosen as ModelDosen;
use Architecture\Shared\TypeData;

class CheckDosenByNIDNQueryHandler extends Query
{
    public function __construct() {}

    public function handle(CheckDosenByNIDNQuery $query)
    {
        return ModelDosen::where("NIDN",$query->GetNIDN())->exists();
    }
}