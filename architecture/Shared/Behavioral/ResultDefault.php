<?php
namespace Architecture\Shared\Behavioral;

class ResultDefault extends MappingHtmlOrmStateInterface{
    public function __construct(public $custom=null){}
    
    public function handle() {
        $this->output = $this->custom;
        return $this;
    }
}