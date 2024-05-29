<?php

namespace Architecture\Application\KlaimAbsen\Update;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\KlaimAbsen\KlaimAbsenBase;
use Architecture\Domain\Entity\Dosen;
use Architecture\Domain\Entity\Pegawai;
use Architecture\Domain\Entity\Presensi;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class UpdateKlaimAbsenCommand extends Command
{
    use IdentityCommand;
    use KlaimAbsenBase;
    public function __construct($id,?Dosen $dosen=null,?Pegawai $pegawai=null,Presensi $presensi,$jam_masuk,$jam_keluar,$tujuan,$dokumen,public TypeData $option = TypeData::Entity) {
        $this->id = $id;
        $this->dosen = $dosen;
        $this->pegawai = $pegawai;
        $this->presensi = $presensi;
        $this->jam_masuk = $jam_masuk;
        $this->jam_keluar = $jam_keluar;
        $this->tujuan = $tujuan;
        $this->dokumen = $dokumen;
    }
}