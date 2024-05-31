<?php

namespace Architecture\Application\SPPD\Update;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\SPPD as ModelSPPD;

class ApprovalSPPDCommandHandler extends CommandHandler
{
    public function handle(ApprovalSPPDCommand $command)
    {
        $SPPD = ModelSPPD::findOrFail($command->GetId());
        $SPPD->status = $command->GetStatus();
        if(!str_contains($command->GetStatus(), "tolak")){
            $SPPD->catatan = null;
        }
        if(!empty($command->GetFile())){
            $SPPD->dokumen_anggaran = $command->GetFile();
        }
        if(!empty($command->GetPIC())){
            $SPPD->id_user = $command->GetPIC();
        }
        if($SPPD->isDirty()) $SPPD->saveOrFail();
    }
}