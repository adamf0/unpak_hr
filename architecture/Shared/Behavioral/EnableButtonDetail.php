<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Enum\TypeStatusPengajuan;

class EnableButtonDetail implements RuleRenderHtmlStartegy{
    public function __construct(public $item, public $level){} 
    public function rule(){
        return in_array($this->level,["dosen","lppm"]) && in_array($this->item->GetStatus(), [TypeStatusPengajuan::TERIMA_PENDANAAN]);
    }
}