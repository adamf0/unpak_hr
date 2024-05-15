<?php

namespace Architecture\Application\VideoKegiatan\Update;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\VideoKegiatan as ModelVideoKegiatan;

class UpdateVideoKegiatanCommandHandler extends CommandHandler
{
    public function handle(UpdateVideoKegiatanCommand $command)
    {
        $VideoKegiatan = ModelVideoKegiatan::findOrFail($command->GetId());
        $VideoKegiatan->id = $command->GetId();
        $VideoKegiatan->nama = $command->GetNama();
        $VideoKegiatan->nilai = $command->GetNilai();
        if($VideoKegiatan->isDirty()) $VideoKegiatan->saveOrFail();
    }
}