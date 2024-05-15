<?php
namespace Architecture\Domain\Contract;

use Architecture\Domain\Entity\BaseEntity;
use Architecture\Domain\Shared\NamingEntity;

abstract class IVideoKegiatan extends BaseEntity{
    use NamingEntity;
    public $nilai = null;

    public function GetNilai(){
        return $this->nilai;
    }
}