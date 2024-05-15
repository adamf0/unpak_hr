<?php

namespace Architecture\Application\JenisIzin\Create;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\JenisIzin as ModelJenisIzin;

class CreateJenisIzinCommandHandler extends CommandHandler
{
    public function handle(CreateJenisIzinCommand $command)
    {
        $JenisIzinBaru = new ModelJenisIzin();
        $JenisIzinBaru->nama = $command->GetNama();
        $JenisIzinBaru->saveOrFail();
    }
}