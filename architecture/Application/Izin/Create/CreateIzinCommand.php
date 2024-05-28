<?php

namespace Architecture\Application\Izin\Create;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\Izin\IzinBase;
use Architecture\Domain\Entity\Dosen;
use Architecture\Domain\Entity\JenisIzin;
use Architecture\Domain\Entity\Pegawai;
use Architecture\Domain\ValueObject\Date;
use Architecture\Shared\TypeData;

class CreateIzinCommand extends Command
{
    use IzinBase;
    public function __construct(
        ?Dosen $dosen=null,
        ?Pegawai $pegawai=null,
        Date $tanggal_pengajuan,
        $tujuan,
        ?JenisIzin $jenis_izin=null,
        $dokumen,
        $status, 
        public TypeData $option = TypeData::Entity
    ) {
        $this->dosen = $dosen;
        $this->pegawai = $pegawai;
        $this->tanggal_pengajuan = $tanggal_pengajuan;
        $this->tujuan = $tujuan;
        $this->jenis_izin = $jenis_izin;
        $this->dokumen = $dokumen;
        $this->status = $status;
    }
}