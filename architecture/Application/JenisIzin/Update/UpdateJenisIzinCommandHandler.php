<?php

namespace Architecture\Application\JenisIzin\Update;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Application\JenisIzin\Update\UpdateJenisIzinCommand;
use Architecture\External\Persistance\ORM\JenisIzin as ModelJenisIzin;

class UpdateJenisIzinCommandHandler extends CommandHandler
{
    public function handle(UpdateJenisIzinCommand $command)
    {
        $JenisIzin = ModelJenisIzin::findOrFail($command->GetId());
        $JenisIzin->nama = $command->GetNama();
        if($JenisIzin->isDirty()) $JenisIzin->saveOrFail();
    }
}