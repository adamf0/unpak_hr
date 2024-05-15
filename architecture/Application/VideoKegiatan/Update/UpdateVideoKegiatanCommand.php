<?php

namespace Architecture\Application\VideoKegiatan\Update;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\NamingCommand;
use Architecture\Shared\TypeData;

class UpdateVideoKegiatanCommand extends Command
{
    use IdentityCommand,NamingCommand;
    public function __construct(public $id, public $nama,public $nilai, public TypeData $option = TypeData::Entity) {}

    public function GetNilai(){
        return $this->nilai;
    }
}