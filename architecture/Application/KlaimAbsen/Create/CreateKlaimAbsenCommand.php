<?php

namespace Architecture\Application\KlaimAbsen\Create;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\KlaimAbsen\KlaimAbsenBase;
use Architecture\Domain\Entity\Dosen;
use Architecture\Domain\Entity\Pegawai;
use Architecture\Domain\Entity\Presensi;
use Architecture\Shared\TypeData;

class CreateKlaimAbsenCommand extends Command
{
    use KlaimAbsenBase;
    public function __construct(?Dosen $dosen=null,?Pegawai $pegawai=null,Presensi $presensi,$jam_masuk,$jam_keluar,$tujuan,$dokumen,public TypeData $option = TypeData::Entity) {
        $this->dosen = $dosen;
        $this->pegawai = $pegawai;
        $this->presensi = $presensi;
        $this->jam_masuk = $jam_masuk;
        $this->jam_keluar = $jam_keluar;
        $this->tujuan = $tujuan;
        $this->dokumen = $dokumen;
    }
}