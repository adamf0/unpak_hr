<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Enum\TypeStatusPengajuan;

class EnableButtonAddReview implements RuleRenderHtmlStartegy{
    public function __construct(public $item, public $level){} 
    public function rule(){
        $ruleLPPM = in_array($this->item->GetStatus(), [TypeStatusPengajuan::MENUNGGU_PILIH_REVIEWER,TypeStatusPengajuan::MENUNGGU_REVIEWER,TypeStatusPengajuan::TERIMA,TypeStatusPengajuan::TERIMA_PENDANAAN,TypeStatusPengajuan::TOLAK_PENDANAAN]);
        return $this->level=="lppm" && $ruleLPPM;
    }
}