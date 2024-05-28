<?php

namespace Architecture\Application\SPPD\Update;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\AnggotaSPPD as ModelAnggotaSPPD;

class UpdateAnggotaSPPDCommandHandler extends CommandHandler
{
    public function handle(UpdateAnggotaSPPDCommand $command)
    {
        $SPPD = ModelAnggotaSPPD::findOrFail($command->GetId());
        $SPPD->id_sppd = $command->GetSPPD()?->GetId();
        $SPPD->nidn = $command->GetDosen()?->GetNidn();
        $SPPD->nip = $command->GetPegawai()?->GetNip();
        if($SPPD->isDirty()) $SPPD->saveOrFail();

        return $SPPD;
    }
}