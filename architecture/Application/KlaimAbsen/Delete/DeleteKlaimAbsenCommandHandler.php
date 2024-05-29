<?php

namespace Architecture\Application\KlaimAbsen\Delete;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\KlaimAbsen as ModelKlaimAbsen;

class DeleteKlaimAbsenCommandHandler extends CommandHandler
{
    public function handle(DeleteKlaimAbsenCommand $command)
    {
        $KlaimAbsen = ModelKlaimAbsen::findOrFail($command->GetId());
        $KlaimAbsen->delete();
    }
}