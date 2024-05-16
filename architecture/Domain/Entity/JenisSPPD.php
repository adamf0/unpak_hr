<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Shared\NamingEntity;

class JenisSPPD extends BaseEntity{
    use NamingEntity;
    public function __construct(public $id=null,public $nama){}
}