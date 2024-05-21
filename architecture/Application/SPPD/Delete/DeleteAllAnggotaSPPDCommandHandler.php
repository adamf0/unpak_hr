<?php

namespace Architecture\Application\SPPD\Delete;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\AnggotaSPPD as ModelAnggotaSPPD;

class DeleteAllAnggotaSPPDCommandHandler extends CommandHandler
{
    public function handle(DeleteAllAnggotaSPPDCommand $command)
    {
        ModelAnggotaSPPD::where('id_sppd',$command->GetIDSPPD())->delete();
    }
}