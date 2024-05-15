<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Enum\TypeStatusPengajuan;

class EnableResultIfAdministrationIsNoExists implements RuleRenderHtmlStartegy{
    public function __construct(public $item){} 
    public function rule(){
        return $this->item->GetStatus()==TypeStatusPengajuan::MENUNGGU_REVIEW_FAKULTAS;
    }
}