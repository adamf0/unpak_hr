<?php

namespace Architecture\Application\Pengguna\Create;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\User as ModelPengguna;
use Illuminate\Support\Facades\Hash;

class CreatePenggunaCommandHandler extends CommandHandler
{
    public function handle(CreatePenggunaCommand $command)
    {
        $PenggunaBaru = new ModelPengguna();
        $PenggunaBaru->username = $command->GetUsername();
        $PenggunaBaru->password = Hash::make($command->GetPassword());
        $PenggunaBaru->level = $command->GetLevel()->val();
        $PenggunaBaru->name = $command->GetNama();
        $PenggunaBaru->saveOrFail();
    }
}