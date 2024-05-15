<?php
namespace Architecture\Shared\Behavioral;

class Result2AdministationInternal extends MappingHtmlOrmStateInterface{
    public function __construct(public $item,public $level){}
    
    public function handle() {
        $this->output .= match(true){
            $this->level=="fakultas" => $this->item->GetAdministrasi()?->GetKeputusan()?->val(),
            $this->level=="lppm" => $this->item->GetAdministrasiLPPM()?->GetKeputusan()?->val(),
            default=> null
        };
        return $this;
    }
}