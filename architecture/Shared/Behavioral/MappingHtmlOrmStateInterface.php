<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Exception\NotImplementedException;

abstract class MappingHtmlOrmStateInterface{
    public $output = '';

    public function handle(){
        throw new NotImplementedException();
    }
    public function GetOutput(){
        return $this->output;
    }
}