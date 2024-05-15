<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Enum\TypeStatusPengajuan;

class ButtonFundingPKMState extends MappingHtmlOrmStateInterface{
    public function __construct(public $item, public $withFlex=false){}
    
    public function handle() {
        if($this->withFlex) $this->output .= "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 col-xxl-5 d-flex justify-content-center mt-2 d-flex justify-content-center'>";
        $this->output .= "
            <form action='".route('pkm.funding')."' method='POST'>
                <input type='hidden' name='type' value='".TypeStatusPengajuan::TERIMA_PENDANAAN->val()."'/>
                <input type='hidden' name='id_pkm' value='".$this->item->GetId()."'/>
                <button type='submit' name='terima' class='btn btn-success'><i class='bi bi-clipboard-check'></i></button>
            </form>
            ";
        if($this->withFlex) $this->output .= "</div>";

        if($this->withFlex) $this->output .= "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 col-xxl-5 d-flex justify-content-center mt-2 d-flex justify-content-center'>";
        $this->output .= "
            <form action='".route('pkm.funding')."' method='POST'>
                <input type='hidden' name='type' value='".TypeStatusPengajuan::TOLAK_PENDANAAN->val()."'/>
                <input type='hidden' name='id_pkm' value='".$this->item->GetId()."'/>
                <button type='submit' name='terima' class='btn btn-danger'><i class='bi bi-clipboard-minus'></i></button>
            </form>
            ";
        if($this->withFlex) $this->output .= "</div>";

        return $this;
    }
}