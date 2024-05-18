<?php

namespace Architecture\Application\Presensi\Create;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\Presensi\PresensiBase;
use Architecture\Domain\ValueObject\Date;
use Architecture\Shared\TypeData;

class CreatePresensiMasukCommand extends Command
{
    use PresensiBase;
    public function __construct($nidn=null,$nip=null,?Date $tanggal=null,?Date $absen_masuk=null,$catatan_telat=null, public TypeData $option = TypeData::Entity) {
        $this->nidn = $nidn;
        $this->nip = $nip;
        $this->tanggal = $tanggal;
        $this->absen_masuk = $absen_masuk;
        $this->catatan_telat = $catatan_telat;
    }
}