<?php
namespace Architecture\Shared\Behavioral;

class EnableResultPointSubstansi implements RuleRenderHtmlStartegy{
    public function __construct(public $item){} 
    public function rule(){
        return $this->item->GetListSubstansi()->count()>0;
    }
}