<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Enum\TypeStatusPengajuan;

class EnableButtonAdministrationIfNoExists implements RuleRenderHtmlStartegy{
    public function __construct(public $item, public $level){} 
    public function rule(){
        $ruleFakultas = in_array($this->item->GetStatus(), [TypeStatusPengajuan::MENUNGGU_REVIEW_FAKULTAS]);
        $ruleLPPM = in_array($this->item->GetStatus(), [TypeStatusPengajuan::MENUNGGU_REVIEW_LPPM]);

        return ($this->level=="fakultas" && is_null($this->item->GetAdministrasi()) && $ruleFakultas) || ($this->level=="lppm" && is_null($this->item->GetAdministrasiLPPM()) && $ruleLPPM);
    }
}