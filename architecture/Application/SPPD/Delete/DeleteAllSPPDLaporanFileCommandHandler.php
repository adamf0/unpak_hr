<?php

namespace Architecture\Application\SPPD\Delete;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\FileLaporanSPPD as ModelFileLaporanSPPD;

class DeleteAllSPPDLaporanFileCommandHandler extends CommandHandler
{
    public function handle(DeleteAllSPPDLaporanFileCommand $command)
    {
        ModelFileLaporanSPPD::where('id_sppd',$command->GetIDSPPD())->where('type',$command->GetType())->delete();
    }
}