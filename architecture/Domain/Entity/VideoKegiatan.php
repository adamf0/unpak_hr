<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Shared\NamingEntity;

class VideoKegiatan extends BaseEntity{
    use NamingEntity;
    public function __construct(public $id=null,public $nama=null,public $nilai=null){}

    public function GetNilai(){
        return $this->nilai;
    }
}