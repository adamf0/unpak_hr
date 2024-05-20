<?php

namespace Architecture\Application\Izin\Count;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\PagingQuery;
use Architecture\Shared\TypeData;

class CountIzinQuery extends Query
{
    use PagingQuery;
    public function __construct(
        public $nidn=null,
        public $nip=null,
        public $status=null
    ) {
        return $this;
    }

    public function GetNIDN(){
        return $this->nidn;
    }
    public function GetNIP(){
        return $this->nip;
    }
    public function GetStatus(){
        return $this->status;
    }
}