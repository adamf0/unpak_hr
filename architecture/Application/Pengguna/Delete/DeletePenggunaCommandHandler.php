<?php

namespace Architecture\Application\Pengguna\Delete;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Application\Pengguna\Delete\DeletePenggunaCommand;
use Architecture\External\Persistance\ORM\User as ModelPengguna;

class DeletePenggunaCommandHandler extends CommandHandler
{
    public function handle(DeletePenggunaCommand $command)
    {
        $Pengguna = ModelPengguna::findOrFail($command->GetId());
        $Pengguna->delete();
    }
}