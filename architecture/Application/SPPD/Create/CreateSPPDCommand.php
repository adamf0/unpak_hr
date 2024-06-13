<?php

namespace Architecture\Application\SPPD\Create;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\SPPD\SPPDBase;
use Architecture\Domain\Entity\Dosen;
use Architecture\Domain\Entity\JenisSPPD;
use Architecture\Domain\Entity\Pegawai;
use Architecture\Domain\ValueObject\Date;
use Architecture\Shared\TypeData;

class CreateSPPDCommand extends Command
{
    use SPPDBase;
    public function __construct(
        ?Dosen $dosen=null,
        ?Pegawai $pegawai=null,
        ?JenisSPPD $jenis_sppd=null,
        ?Date $tanggal_berangkat=null,
        ?Date $tanggal_kembali=null,
        $tujuan,
        $keterangan,
        $sarana_transportasi,
        ?Pegawai $verifikasi=null,
        $status,
        public TypeData $option = TypeData::Entity
    ) {
        $this->dosen = $dosen;
        $this->pegawai = $pegawai;
        $this->jenis_sppd = $jenis_sppd;
        $this->tanggal_berangkat = $tanggal_berangkat;
        $this->tanggal_kembali = $tanggal_kembali;
        $this->tujuan = $tujuan;
        $this->keterangan = $keterangan;
        $this->sarana_transportasi = $sarana_transportasi;
        $this->verifikasi = $verifikasi;
        $this->status = $status;
    }
}