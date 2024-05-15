<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Enum\TypeStatusPengajuan;

class EnableButtonSubstansiIfExists implements RuleRenderHtmlStartegy{
    public function __construct(public $item, public $level){} 
    public function rule(){
        $ruleLppm = $this->level=="reviewer" && in_array($this->item->GetStatus(), [TypeStatusPengajuan::MENUNGGU_REVIEWER]);
        return $ruleLppm;
    }
}