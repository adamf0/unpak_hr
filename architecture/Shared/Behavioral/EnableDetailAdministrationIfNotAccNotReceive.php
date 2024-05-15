<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Enum\TypeKeputusanAdministrasi;
use Illuminate\Support\Collection;

class EnableDetailAdministrationIfNotAccNotReceive implements RuleRenderHtmlStartegy{
    public function __construct(public $item, public ?Collection $pointRevisi=null){} 
    public function rule(){
        // return $this->item->GetAdministrasi()?->GetKeputusan()!= null && 
        return ($this->item->GetAdministrasi()?->GetKeputusan()!=TypeKeputusanAdministrasi::DITERIMA || ($this->pointRevisi!=null && $this->pointRevisi->count()>0));
    }
}