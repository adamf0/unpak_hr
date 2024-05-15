<?php

namespace Architecture\Application\JenisIzin\Delete;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Application\JenisIzin\Delete\DeleteJenisIzinCommand;
use Architecture\External\Persistance\ORM\JenisIzin as ModelJenisIzin;

class DeleteJenisIzinCommandHandler extends CommandHandler
{
    public function handle(DeleteJenisIzinCommand $command)
    {
        $JenisIzin = ModelJenisIzin::findOrFail($command->GetId());
        $JenisIzin->delete();
    }
}