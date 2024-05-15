<?php

namespace Architecture\Application\VideoKegiatan\Create;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\VideoKegiatan as ModelVideoKegiatan;

class CreateVideoKegiatanCommandHandler extends CommandHandler
{
    public function handle(CreateVideoKegiatanCommand $command)
    {
        $VideoKegiatanBaru = new ModelVideoKegiatan();
        $VideoKegiatanBaru->nama = $command->GetNama();
        $VideoKegiatanBaru->nilai = $command->GetNilai();
        $VideoKegiatanBaru->saveOrFail();
    }
}