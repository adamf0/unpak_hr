<?php

namespace Architecture\Application\MasterKalendar\Delete;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Application\MasterKalendar\Delete\DeleteMasterKalendarCommand;
use Architecture\External\Persistance\ORM\MasterKalendar as ModelMasterKalendar;

class DeleteMasterKalendarCommandHandler extends CommandHandler
{
    public function handle(DeleteMasterKalendarCommand $command)
    {
        $MasterKalendar = ModelMasterKalendar::findOrFail($command->GetId());
        $MasterKalendar->delete();
    }
}