<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Enum\TypeStatusAnggota;
use Architecture\Domain\Enum\TypeStatusPengajuan;

class EnableButtonEditorFormIfMember implements RuleRenderHtmlStartegy{
    public function __construct(public $item, public $level, public $nidn, public ?TypeStatusAnggota $statusVerifikasi=null){} 
    public function rule(){
        $ruleStatusAnggotaDosen = in_array($this->item->GetStatus(), [TypeStatusPengajuan::MENUNGGU_VERIF_ANGGOTA, TypeStatusPengajuan::TOLAK_ANGGOTA]);
        return $this->level=="dosen" && $this->nidn!=$this->item->GetDosen()->GetNidn() && $this->statusVerifikasi!=TypeStatusAnggota::Diterima && $ruleStatusAnggotaDosen;
    }
}