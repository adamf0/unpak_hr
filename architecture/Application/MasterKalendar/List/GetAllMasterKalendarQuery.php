<?php

namespace Architecture\Application\MasterKalendar\List;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\PagingQuery;
use Architecture\Shared\TypeData;

class GetAllMasterKalendarQuery extends Query
{
    use PagingQuery;
    public function __construct(
        public $tanggal,
        public $tahun_sebelum=0,
        public $tahun_sesudah=0,
        public TypeData $option = TypeData::Entity
    ) {
        return $this;
    }

    public function GetTanggal(){
        return $this->tanggal;
    }
    public function GetTahunSebelum(){
        return $this->tahun_sebelum;
    }
    public function GetTahunSesudah(){
        return $this->tahun_sesudah;
    }
}