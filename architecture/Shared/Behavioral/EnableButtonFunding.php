<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Enum\TypeStatusPengajuan;

class EnableButtonFunding implements RuleRenderHtmlStartegy{
    public function __construct(public $item, public $level){} 
    public function rule(){
        $ruleLPPM = in_array($this->item->GetStatus(), [TypeStatusPengajuan::TERIMA,TypeStatusPengajuan::TERIMA_PENDANAAN,TypeStatusPengajuan::TOLAK_PENDANAAN]);
        return $this->level=="lppm" && $ruleLPPM;
    }
}