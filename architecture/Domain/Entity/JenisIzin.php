<?php
namespace Architecture\Domain\Entity;

use Architecture\Application\JenisCuti\JenisCutiBase;

class JenisIzin extends BaseEntity{
    use JenisCutiBase;
    public function __construct(public $id=null,public $nama){}
}