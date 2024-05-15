<?php

namespace Architecture\Application\Cuti\Delete;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\Cuti as ModelCuti;

class DeleteCutiCommandHandler extends CommandHandler
{
    public function handle(DeleteCutiCommand $command)
    {
        $Cuti = ModelCuti::findOrFail($command->GetId());
        $Cuti->delete();
    }
}