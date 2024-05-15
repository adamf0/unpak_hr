<?php
namespace Architecture\Shared\Behavioral;

class ButtonAdministrationEditorPkmState extends MappingHtmlOrmStateInterface{
    public function __construct(public $item, public $type){}
    
    public function handle() {
        $this->output = '<a href="'.route('pkm.administration',['type'=>$this->type,'id_pkm'=>$this->item->GetId()]).'" class="btn btn-info"><i class="bi bi-file-earmark-check"></i></a>';

        return $this;
    }
}