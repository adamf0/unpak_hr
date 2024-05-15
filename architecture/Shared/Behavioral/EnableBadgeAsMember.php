<?php
namespace Architecture\Shared\Behavioral;

class EnableBadgeAsMember implements RuleRenderHtmlStartegy{
    public function __construct(public $item, public $level, public $nidn){} 
    public function rule(){
        return $this->level == "dosen" && !empty($this->nidn) && $this->nidn != $this->item->GetDosen()->GetNidn();
    }
}