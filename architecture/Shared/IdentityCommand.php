<?php

namespace Architecture\Shared;

trait IdentityCommand
{
    public $id=null;
    public function GetId(){
        return $this->id;
    }
}
