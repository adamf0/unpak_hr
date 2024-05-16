<?php

namespace Architecture\Application\Cuti\Update;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\Cuti as ModelCuti;

class ApprovalCutiCommandHandler extends CommandHandler
{
    public function handle(ApprovalCutiCommand $command)
    {
        $Cuti = ModelCuti::findOrFail($command->GetId());
        $Cuti->status = $command->GetStatus();
        if($Cuti->isDirty()) $Cuti->saveOrFail();
    }
}