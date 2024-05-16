<?php

namespace Architecture\Application\SPPD\Delete;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\SPPD as ModelSPPD;

class DeleteSPPDCommandHandler extends CommandHandler
{
    public function handle(DeleteSPPDCommand $command)
    {
        $SPPD = ModelSPPD::findOrFail($command->GetId());
        $SPPD->delete();
    }
}