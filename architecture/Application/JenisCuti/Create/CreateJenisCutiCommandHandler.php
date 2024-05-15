<?php

namespace Architecture\Application\JenisCuti\Create;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\JenisCuti as ModelJenisCuti;

class CreateJenisCutiCommandHandler extends CommandHandler
{
    public function handle(CreateJenisCutiCommand $command)
    {
        $JenisCutiBaru = new ModelJenisCuti();
        $JenisCutiBaru->nama = $command->GetNama();
        $JenisCutiBaru->min = $command->GetMin();
        $JenisCutiBaru->max = $command->GetMax();
        $JenisCutiBaru->dokumen = $command->GetDokumen();
        $JenisCutiBaru->kondisi = $command->GetKondisi()??"{}";
        $JenisCutiBaru->saveOrFail();
    }
}