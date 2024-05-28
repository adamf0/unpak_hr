<?php

namespace Architecture\Application\SPPD\Create;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\SPPD\SPPDBase;
use Architecture\Domain\Entity\Dosen;
use Architecture\Domain\Entity\Pegawai;
use Architecture\Domain\Entity\SPPD;
use Architecture\Shared\TypeData;

class CreateAnggotaSPPDCommand extends Command
{
    use SPPDBase;
    public function __construct(
        public SPPD $sppd,
        ?Dosen $dosen=null,
        ?Pegawai $pegawai=null,
        public TypeData $option = TypeData::Entity
    ) {
        $this->sppd = $sppd;
        $this->dosen = $dosen;
        $this->pegawai = $pegawai;
    }

    public function GetSPPD(){
        return $this->sppd;
    }
}