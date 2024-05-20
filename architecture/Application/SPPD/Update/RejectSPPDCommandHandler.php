<?php

namespace Architecture\Application\SPPD\Update;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\SPPD as ModelSPPD;

class RejectSPPDCommandHandler extends CommandHandler
{
    public function handle(RejectSPPDCommand $command)
    {
        $SPPD = ModelSPPD::findOrFail($command->GetId());
        $SPPD->status = 'tolak';
        if(!empty($command->GetCatatan())){
            $SPPD->catatan = $command->GetCatatan();
        }
        if($SPPD->isDirty()) $SPPD->saveOrFail();
    }
}