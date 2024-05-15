<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Enum\TypeStatusPengajuan;

class EnableStatusWaitingAsOwnData implements RuleRenderHtmlStartegy{
    public function __construct(public $item, public $level, public $nidn){} 
    public function rule(){
        return in_array($this->item->GetStatus(),[TypeStatusPengajuan::MENUNGGU_VERIF_ANGGOTA, TypeStatusPengajuan::TOLAK_ANGGOTA]) && $this->level=="dosen" && $this->nidn==$this->item->GetDosen()->GetNidn();
    }
}