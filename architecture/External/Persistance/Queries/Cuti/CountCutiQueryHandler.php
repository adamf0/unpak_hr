<?php

namespace Architecture\External\Persistance\Queries\Cuti;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Cuti\Count\CountCutiQuery as CountCutiQuery;
use Illuminate\Support\Facades\DB;

class CountCutiQueryHandler extends Query
{
    public function __construct() {}

    public function handle(CountCutiQuery $query)
    {
        $cuti = DB::table('cuti');
        if(!is_null($query->GetNIDN())){
            $cuti->where('nidn',$query->GetNIDN());
        }
        if(!is_null($query->GetNIP())){
            $cuti->where('nip',$query->GetNIP());
        }
        if(!is_null($query->GetStatus())){
            $cuti->where('status',$query->GetStatus());
        }
        return $cuti->count();
    }
}