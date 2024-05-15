<?php

namespace Architecture\Application\JenisCuti\Update;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Application\JenisCuti\Update\UpdateJenisCutiCommand;
use Architecture\External\Persistance\ORM\JenisCuti as ModelJenisCuti;

class UpdateJenisCutiCommandHandler extends CommandHandler
{
    public function handle(UpdateJenisCutiCommand $command)
    {
        $JenisCuti = ModelJenisCuti::findOrFail($command->GetId());
        $JenisCuti->min = $command->GetMin();
        $JenisCuti->max = $command->GetMax();
        $JenisCuti->dokumen = $command->GetDokumen();
        $JenisCuti->kondisi = $command->GetKondisi()??"{}";
        if($JenisCuti->isDirty()) $JenisCuti->saveOrFail();
    }
}