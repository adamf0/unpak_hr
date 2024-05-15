<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Enum\TypeStatusPengajuan;

class EnableResultIfAdministrationIsExists implements RuleRenderHtmlStartegy{
    public function __construct(public $item){} 
    public function rule(){
        // return $this->item->GetAdministrasi()!=null && 
        return in_array($this->item->GetStatus(), [TypeStatusPengajuan::MENUNGGU_REVIEW_LPPM,TypeStatusPengajuan::MENUNGGU_PILIH_REVIEWER,TypeStatusPengajuan::MENUNGGU_REVIEWER,TypeStatusPengajuan::TERIMA,TypeStatusPengajuan::TOLAK,TypeStatusPengajuan::TERIMA_PENDANAAN]);
    }
}