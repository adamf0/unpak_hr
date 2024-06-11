<?php

namespace Architecture\Application\SPPD\Update;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class UpdateSPPDLaporanCommand extends Command
{
    use IdentityCommand;
    public function __construct(
        public $id,
        public $intisari=null,
        public $kontribusi=null,
        public $rencana_tindak_lanjut=null,
        public $rencana_waktu_tindak_lanjut=null, 
        public TypeData $option = TypeData::Entity
    ) {}

    public function GetIntisari(){
        return $this->intisari;
    }
    public function GetKontribusi(){
        return $this->kontribusi;
    }
    public function GetRencanaTindakLanjut(){
        return $this->rencana_tindak_lanjut;
    }
    public function GetRencanaWaktuTindakLanjut(){
        return $this->rencana_waktu_tindak_lanjut;
    }
}