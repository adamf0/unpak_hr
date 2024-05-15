<?php

namespace Architecture\Application\VideoKegiatan\Delete;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\VideoKegiatan as ModelVideoKegiatan;

class DeleteVideoKegiatanCommandHandler extends CommandHandler
{
    public function handle(DeleteVideoKegiatanCommand $command)
    {
        $VideoKegiatan = ModelVideoKegiatan::findOrFail($command->GetId());
        $VideoKegiatan->delete();
    }
}