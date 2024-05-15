<?php

namespace Architecture\Application\JenisCuti;

use Architecture\Domain\Shared\NamingEntity;

trait JenisCutiBase 
{
    use NamingEntity;
    public $min;
    public $max;
    public $dokumen;
    public $kondisi=null;

    public function GetMin(){
        return $this->min;
    }
    public function GetMax(){
        return $this->max;
    }
    public function GetDokumen(){
        return $this->dokumen;
    }
    public function GetKondisi(){
        return $this->kondisi;
    }
}