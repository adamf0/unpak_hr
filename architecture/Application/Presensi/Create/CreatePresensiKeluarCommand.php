<?php

namespace Architecture\Application\Presensi\Create;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\Presensi\PresensiBase;
use Architecture\Domain\ValueObject\Date;
use Architecture\Shared\TypeData;

class CreatePresensiKeluarCommand extends Command
{
    use PresensiBase;
    public function __construct($nidn=null,$nip=null,?Date $tanggal=null,?Date $absen_keluar=null,$catatan_pulang=null, public TypeData $option = TypeData::Entity) {
        $this->nidn = $nidn;
        $this->nip = $nip;
        $this->tanggal = $tanggal;
        $this->absen_keluar = $absen_keluar;
        $this->catatan_pulang = $catatan_pulang;
    }
}