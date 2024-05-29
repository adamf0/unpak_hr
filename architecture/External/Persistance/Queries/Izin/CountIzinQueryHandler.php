<?php

namespace Architecture\External\Persistance\Queries\Izin;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Application\Izin\Count\CountIzinQuery as CountIzinQuery;
use Illuminate\Support\Facades\DB;

class CountIzinQueryHandler extends Query
{
    public function __construct() {}

    public function handle(CountIzinQuery $query)
    {
        $izin = DB::table('izin');
        if(!is_null($query->GetNIDN())){
            $izin->where('nidn',$query->GetNIDN());
        }
        if(!is_null($query->GetNIP())){
            $izin->where('nip',$query->GetNIP());
        }
        if(!is_null($query->GetStatus())){
            $izin->where('status',$query->GetStatus());
        }
        return $izin->count();
    }
}