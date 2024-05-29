<?php

namespace Architecture\Application\KlaimAbsen\Count;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\PagingQuery;

class CountKlaimAbsenQuery extends Query
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