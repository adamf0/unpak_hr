<?php

namespace Architecture\Application\MasterKalendar\Update;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\MasterKalendar\MasterKalendarBase;
use Architecture\Domain\ValueObject\Date;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class UpdateMasterKalendarCommand extends Command
{
    use IdentityCommand,MasterKalendarBase;
    public function __construct(public $id, ?Date $tanggal_mulai=null, ?Date $tanggal_berakhir=null, $keterangan=null, public TypeData $option = TypeData::Entity) {
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_berakhir = $tanggal_berakhir;
        $this->keterangan = $keterangan;
    }
}