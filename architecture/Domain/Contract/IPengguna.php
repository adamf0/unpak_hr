<?php
namespace Architecture\Domain\Contract;

use Architecture\Domain\Entity\BaseEntity;
use Architecture\Domain\Shared\NamingEntity;

abstract class IPengguna extends BaseEntity{
    use NamingEntity;
    public $nidn=null;
    public $nip=null;
    public $username=null;
    public $password=null;
    public $faculty=null;
    public $programStudy=null;
    public $position=null;
    public $level=null;
    public $active=true;

    public function GetNIDN(){
        return $this->nidn;
    }
    public function GetNIP(){
        return $this->nip;
    }
    public function GetUsername(){
        return $this->username;
    }
    public function GetPassword(){
        return $this->password;
    }
    public function GetFaculty(){
        return $this->faculty;
    }
    public function GetProgramStudy(){
        return $this->programStudy;
    }
    public function GetPosition(){
        return $this->position;
    }
    public function GetLevel(){
        return $this->level;
    }
    public function GetActive(){
        return $this->active;
    }
}