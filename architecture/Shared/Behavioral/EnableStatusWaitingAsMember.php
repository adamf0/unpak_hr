<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Enum\TypeStatusPengajuan;

class EnableStatusWaitingAsMember implements RuleRenderHtmlStartegy{
    public function __construct(public $item, public $level, public $nidn){} 
    public function rule(){
        return $this->item->GetStatus()==TypeStatusPengajuan::MENUNGGU_VERIF_ANGGOTA && $this->level=="dosen" && !empty($this->nidn) && $this->nidn!=$this->item->GetDosen()->GetNidn();
    }
}