<?php

namespace Architecture\Application\Izin\Update;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\Izin\IzinBase;
use Architecture\Domain\Entity\Dosen;
use Architecture\Domain\Entity\JenisIzin;
use Architecture\Domain\Entity\Pegawai;
use Architecture\Domain\ValueObject\Date;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class UpdateIzinCommand extends Command
{
    use IdentityCommand,IzinBase;
    public function __construct(
        $id,
        ?Dosen $dosen=null,
        ?Pegawai $pegawai=null,
        Date $tanggal_pengajuan,
        $tujuan,
        ?JenisIzin $jenis_izin=null,
        $dokumen,
        $status, 
        public TypeData $option = TypeData::Entity
    ) {
        $this->id = $id;
        $this->dosen = $dosen;
        $this->pegawai = $pegawai;
        $this->tanggal_pengajuan = $tanggal_pengajuan;
        $this->tujuan = $tujuan;
        $this->jenis_izin = $jenis_izin;
        $this->dokumen = $dokumen;
        $this->status = $status;
    }
}