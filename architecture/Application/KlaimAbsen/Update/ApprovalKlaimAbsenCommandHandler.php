<?php

namespace Architecture\Application\KlaimAbsen\Update;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\KlaimAbsen as ModelKlaimAbsen;

class ApprovalKlaimAbsenCommandHandler extends CommandHandler
{
    public function handle(ApprovalKlaimAbsenCommand $command)
    {
        $KlaimAbsen = ModelKlaimAbsen::findOrFail($command->GetId());
        $KlaimAbsen->status = $command->GetStatus();
        $KlaimAbsen->catatan = $command->GetCatatan();
        if(!empty($command->GetPIC())){
            $KlaimAbsen->id_user = $command->GetPIC();
        }
        if($KlaimAbsen->isDirty()) $KlaimAbsen->saveOrFail();
    }
}