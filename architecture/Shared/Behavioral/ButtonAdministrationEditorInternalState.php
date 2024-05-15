<?php
namespace Architecture\Shared\Behavioral;

class ButtonAdministrationEditorInternalState extends MappingHtmlOrmStateInterface{
    public function __construct(public $item, public $type){}
    
    public function handle() {
        $this->output = '<a href="'.route('penelitianInternal.administration',['type'=>$this->type,'id_penelitian_internal'=>$this->item->GetId()]).'" class="btn btn-info"><i class="bi bi-file-earmark-check"></i></a>';

        return $this;
    }
}