<?php

namespace Architecture\Application\VideoKegiatan\Create;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Shared\NamingCommand;
use Architecture\Shared\TypeData;

class CreateVideoKegiatanCommand extends Command
{
    use NamingCommand;
    public function __construct(public $nama,public $nilai, public TypeData $option = TypeData::Entity) {}

    public function GetNilai(){
        return $this->nilai;
    }
}