<?php

namespace Architecture\Application\SPPD\Update;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\SPPD\SPPDBase;
use Architecture\Domain\Entity\Dosen;
use Architecture\Domain\Entity\JenisSPPD;
use Architecture\Domain\Entity\Pegawai;
use Architecture\Domain\ValueObject\Date;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class UpdateSPPDCommand extends Command
{
    use IdentityCommand,SPPDBase;
    public function __construct(
        $id,
        ?Dosen $dosen=null,
        ?Pegawai $pegawai=null,
        ?JenisSPPD $jenis_sppd=null,
        ?Date $tanggal_berangkat=null,
        ?Date $tanggal_kembali=null,
        $tujuan,
        $keterangan,
        ?Pegawai $verifikasi=null,
        $status,
        public TypeData $option = TypeData::Entity
    ) {
        $this->id = $id;
        $this->dosen = $dosen;
        $this->pegawai = $pegawai;
        $this->jenis_sppd = $jenis_sppd;
        $this->tanggal_berangkat = $tanggal_berangkat;
        $this->tanggal_kembali = $tanggal_kembali;
        $this->tujuan = $tujuan;
        $this->keterangan = $keterangan;
        $this->verifikasi = $verifikasi;
        $this->status = $status;
    }
}