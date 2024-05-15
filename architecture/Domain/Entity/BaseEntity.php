<?php
namespace Architecture\Domain\Entity;

class BaseEntity{
    public $id=null;

    public function GetId(){
        return $this->id;
    }
}