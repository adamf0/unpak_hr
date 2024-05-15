<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Enum\TypeStatusPengajuan;

class EnableButtonAdministrationIfExists implements RuleRenderHtmlStartegy{
    public function __construct(public $item, public $level){} 
    public function rule(){
        $ruleFakultas = in_array($this->item->GetStatus(), [TypeStatusPengajuan::MENUNGGU_REVIEW_FAKULTAS]);
        $ruleLPPM = in_array($this->item->GetStatus(), [TypeStatusPengajuan::MENUNGGU_REVIEW_LPPM]);
        
        return ($this->level=="fakultas" && $ruleFakultas) || ($this->level=="lppm" && $ruleLPPM);
    }
}