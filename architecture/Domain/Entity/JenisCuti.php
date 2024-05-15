<?php
namespace Architecture\Domain\Entity;

use Architecture\Application\JenisCuti\JenisCutiBase;

class JenisCuti extends BaseEntity{
    use JenisCutiBase;
    public function __construct(public $id=null,public $nama,public $min,public $max,public $dokumen,public $kondisi=null){}
}