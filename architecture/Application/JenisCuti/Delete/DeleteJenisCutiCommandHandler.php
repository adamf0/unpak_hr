<?php

namespace Architecture\Application\JenisCuti\Delete;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Application\JenisCuti\Delete\DeleteJenisCutiCommand;
use Architecture\External\Persistance\ORM\JenisCuti as ModelJenisCuti;

class DeleteJenisCutiCommandHandler extends CommandHandler
{
    public function handle(DeleteJenisCutiCommand $command)
    {
        $JenisCuti = ModelJenisCuti::findOrFail($command->GetId());
        $JenisCuti->delete();
    }
}