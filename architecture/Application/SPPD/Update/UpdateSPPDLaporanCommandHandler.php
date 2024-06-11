<?php

namespace Architecture\Application\SPPD\Update;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\SPPD as ModelSPPD;

class UpdateSPPDLaporanCommandHandler extends CommandHandler
{
    public function handle(UpdateSPPDLaporanCommand $command)
    {
        $SPPD = ModelSPPD::findOrFail($command->GetId());
        $SPPD->intisari = $command->GetIntisari();
        $SPPD->kontribusi = $command->GetKontribusi();
        $SPPD->rencana_tindak_lanjut = $command->GetRencanaTindakLanjut();
        $SPPD->rencana_waktu_tindak_lanjut = $command->GetRencanaWaktuTindakLanjut();
        if($SPPD->isDirty()) $SPPD->saveOrFail();
    }
}