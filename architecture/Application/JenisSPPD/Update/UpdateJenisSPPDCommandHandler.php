<?php

namespace Architecture\Application\JenisSPPD\Update;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Application\JenisSPPD\Update\UpdateJenisSPPDCommand;
use Architecture\External\Persistance\ORM\JenisSPPD as ModelJenisSPPD;

class UpdateJenisSPPDCommandHandler extends CommandHandler
{
    public function handle(UpdateJenisSPPDCommand $command)
    {
        $JenisSPPD = ModelJenisSPPD::findOrFail($command->GetId());
        $JenisSPPD->nama = $command->GetNama();
        if($JenisSPPD->isDirty()) $JenisSPPD->saveOrFail();
    }
}