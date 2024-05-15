<?php
namespace Architecture\Shared\Behavioral;

class Result1AdministationInternal extends MappingHtmlOrmStateInterface{
    public function __construct(public $level){}
    
    public function handle() {
        $this->output .= match($this->level){
            "fakultas" => "belum ada administrasi dari fakultas",
            "lppm" => "belum ada administrasi dari LPPM",
            default => "belum ada administrasi dari N/A"
        };
        return $this;
    }
}