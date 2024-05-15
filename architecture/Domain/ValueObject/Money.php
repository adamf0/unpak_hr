<?php
namespace Architecture\Domain\ValueObject;

use Exception;

class Money {
    function __construct(private $value = 0.0) {}

    function val(){
        return $this->value;
    }
    function formatted($digit = 2){
        if($this->value==null || $this->value=='') return "";
            // throw new Exception("value of money can't be null or empty");

        return "Rp " . number_format($this->value,$digit,',','.');
    }
}