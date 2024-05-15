<?php
namespace Architecture\Domain\ValueObject;

class Percent {
    function __construct(private $value = 0) {}

    function val(){
        return $this->value;
    }
    function formatted(){
        return $this->value."%";
    }
}