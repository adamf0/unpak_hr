<?php

namespace Architecture\Application\JenisSPPD\Delete;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Application\JenisSPPD\Delete\DeleteJenisSPPDCommand;
use Architecture\External\Persistance\ORM\JenisSPPD as ModelJenisSPPD;

class DeleteJenisSPPDCommandHandler extends CommandHandler
{
    public function handle(DeleteJenisSPPDCommand $command)
    {
        $JenisSPPD = ModelJenisSPPD::findOrFail($command->GetId());
        $JenisSPPD->delete();
    }
}