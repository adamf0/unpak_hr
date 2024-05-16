<?php

namespace Architecture\Application\Izin\Delete;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Application\Izin\Delete\DeleteIzinCommand;
use Architecture\External\Persistance\ORM\Izin as ModelIzin;

class DeleteIzinCommandHandler extends CommandHandler
{
    public function handle(DeleteIzinCommand $command)
    {
        $Izin = ModelIzin::findOrFail($command->GetId());
        $Izin->delete();
    }
}