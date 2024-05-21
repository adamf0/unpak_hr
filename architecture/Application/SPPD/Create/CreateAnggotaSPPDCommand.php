<?php

namespace Architecture\Application\SPPD\Create;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\SPPD\SPPDBase;
use Architecture\Shared\TypeData;

class CreateAnggotaSPPDCommand extends Command
{
    use SPPDBase;
    public function __construct(
        public $id_sppd,
        $nidn,
        $nip,
        public TypeData $option = TypeData::Entity
    ) {
        $this->id_sppd = $id_sppd;
        $this->nidn = $nidn;
        $this->nip = $nip;
    }

    public function GetIDSPPD(){
        return $this->id_sppd;
    }
}