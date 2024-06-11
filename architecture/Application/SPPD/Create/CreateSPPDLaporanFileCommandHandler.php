<?php

namespace Architecture\Application\SPPD\Create;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\FileLaporanSPPD as ModelFileLaporanSPPD;

class CreateSPPDLaporanFileCommandHandler extends CommandHandler
{
    public function handle(CreateSPPDLaporanFileCommand $command)
    {
        return ModelFileLaporanSPPD::insert($command->GetRecords());
    }
}