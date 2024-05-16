<?php

namespace Architecture\Application\JenisSPPD\Create;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\JenisSPPD as ModelJenisSPPD;

class CreateJenisSPPDCommandHandler extends CommandHandler
{
    public function handle(CreateJenisSPPDCommand $command)
    {
        $JenisSPPDBaru = new ModelJenisSPPD();
        $JenisSPPDBaru->nama = $command->GetNama();
        $JenisSPPDBaru->saveOrFail();
    }
}