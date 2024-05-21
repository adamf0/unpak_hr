<?php

namespace Architecture\Application\SPPD\Update;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\SPPD\SPPDBase;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class UpdateAnggotaSPPDCommand extends Command
{
    use IdentityCommand,SPPDBase;
    public function __construct(
        $id,
        public $id_sppd,
        $nidn,
        $nip,
        public TypeData $option = TypeData::Entity
    ) {
        $this->id = $id;
        $this->id_sppd = $id_sppd;
        $this->nidn = $nidn;
        $this->nip = $nip;
    }

    public function GetIDSPPD(){
        return $this->id_sppd;
    }
}