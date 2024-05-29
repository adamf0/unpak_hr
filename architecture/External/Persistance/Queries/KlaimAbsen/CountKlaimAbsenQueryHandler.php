<?php

namespace Architecture\External\Persistance\Queries\KlaimAbsen;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\KlaimAbsen\Count\CountKlaimAbsenQuery as CountKlaimAbsenQuery;
use Illuminate\Support\Facades\DB;

class CountKlaimAbsenQueryHandler extends Query
{
    public function __construct() {}

    public function handle(CountKlaimAbsenQuery $query)
    {
        $KlaimAbsen = DB::table('klaim_absen');
        if(!is_null($query->GetNIDN())){
            $KlaimAbsen->where('nidn',$query->GetNIDN());
        }
        if(!is_null($query->GetNIP())){
            $KlaimAbsen->where('nip',$query->GetNIP());
        }
        if(!is_null($query->GetStatus())){
            $KlaimAbsen->where('status',$query->GetStatus());
        }
        return $KlaimAbsen->count();
    }
}