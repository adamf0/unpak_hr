<?php

namespace Architecture\Application\Izin\Update;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\Izin as ModelIzin;

class ApprovalIzinCommandHandler extends CommandHandler
{
    public function handle(ApprovalIzinCommand $command)
    {
        $Izin = ModelIzin::findOrFail($command->GetId());
        $Izin->status = $command->GetStatus();
        $Izin->catatan = $command->GetCatatan();
        if(!empty($command->GetPIC())){
            $Izin->id_user = $command->GetPIC();
        }
        if($Izin->isDirty()) $Izin->saveOrFail();
    }
}