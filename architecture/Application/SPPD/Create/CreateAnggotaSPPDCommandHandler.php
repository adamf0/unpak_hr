<?php

namespace Architecture\Application\SPPD\Create;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\AnggotaSPPD as ModelAnggotaSPPD;

class CreateAnggotaSPPDCommandHandler extends CommandHandler
{
    public function handle(CreateAnggotaSPPDCommand $command)
    {
        $Anggota = new ModelAnggotaSPPD();
        $Anggota->id_sppd = $command->GetIDSPPD();
        $Anggota->nidn = $command->GetNIDN();
        $Anggota->nip = $command->GetNIP();
        $Anggota->saveOrFail();
        
        return $Anggota;
    }
}