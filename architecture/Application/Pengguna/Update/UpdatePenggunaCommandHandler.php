<?php

namespace Architecture\Application\Pengguna\Update;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Application\Pengguna\Update\UpdatePenggunaCommand;
use Architecture\External\Persistance\ORM\User as ModelPengguna;
use Illuminate\Support\Facades\Hash;

class UpdatePenggunaCommandHandler extends CommandHandler
{
    public function handle(UpdatePenggunaCommand $command)
    {
        $Pengguna = ModelPengguna::findOrFail($command->GetId());
        $Pengguna->username = $command->GetUsername();
        if(!empty($command->GetPassword())){
            $Pengguna->password = Hash::make($command->GetPassword());
        }
        $Pengguna->level = $command->GetLevel()->val();
        $Pengguna->name = $command->GetNama();
        if($Pengguna->isDirty()) $Pengguna->saveOrFail();
    }
}