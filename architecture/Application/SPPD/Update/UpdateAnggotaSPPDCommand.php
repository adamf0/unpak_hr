<?php

namespace Architecture\Application\SPPD\Update;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\SPPD\SPPDBase;
use Architecture\Domain\Entity\Dosen;
use Architecture\Domain\Entity\Pegawai;
use Architecture\Domain\Entity\SPPD;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class UpdateAnggotaSPPDCommand extends Command
{
    use IdentityCommand,SPPDBase;
    public function __construct(
        $id,
        public SPPD $sppd,
        ?Dosen $dosen,
        ?Pegawai $pegawai,
        public TypeData $option = TypeData::Entity
    ) {
        $this->id = $id;
        $this->sppd = $sppd;
        $this->dosen = $dosen;
        $this->pegawai = $pegawai;
    }

    public function GetSPPD(){
        return $this->sppd;
    }
}