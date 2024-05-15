<?php
namespace Architecture\Shared\Behavioral;

class ButtonEditorPkmAsMemberState extends MappingHtmlOrmStateInterface{
    public function __construct(public $item, public $withFlex=false){}
    
    public function handle() {
        if($this->withFlex) $this->output .= "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 col-xxl-5 d-flex justify-content-center mt-2 d-flex justify-content-center'>";
        $this->output .= "
            <form action='".route('pkm.verificationMember')."' method='POST'>
                <input type='hidden' name='status' value='1'/>
                <input type='hidden' name='id_pkm' value='".$this->item->GetId()."'/>
                <button type='submit' name='terima' class='btn btn-success'><i class='bi bi-check-circle'></i></button>
            </form>
            ";
        if($this->withFlex) $this->output .= "</div>";

        if($this->withFlex) $this->output .= "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 col-xxl-5 d-flex justify-content-center mt-2 d-flex justify-content-center'>";
        $this->output .= "
            <form action='".route('pkm.verificationMember')."' method='POST'>
                <input type='hidden' name='status' value='0'/>
                <input type='hidden' name='id_pkm' value='".$this->item->GetId()."'/>
                <button type='submit' name='terima' class='btn btn-danger'><i class='bi bi-x-circle'></i></button>
            </form>
            ";
        if($this->withFlex) $this->output .= "</div>";

        return $this;
    }
}