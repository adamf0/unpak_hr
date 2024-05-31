<?php

namespace Architecture\Application\Presensi\Create;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\Presensi\PresensiBase;
use Architecture\Domain\Entity\Dosen;
use Architecture\Domain\Entity\Pegawai;
use Architecture\Domain\ValueObject\Date;
use Architecture\Shared\TypeData;

class CreatePresensiKeluarCommand extends Command
{
    use PresensiBase;
    public function __construct(?Dosen $dosen=null,?Pegawai $pegawai=null,?Date $tanggal=null,?Date $absen_keluar=null,$catatan_pulang=null, public TypeData $option = TypeData::Entity) {
        $this->dosen = $dosen;
        $this->pegawai = $pegawai;
        $this->tanggal = $tanggal;
        $this->absen_keluar = $absen_keluar;
        $this->catatan_pulang = $catatan_pulang;
    }
}