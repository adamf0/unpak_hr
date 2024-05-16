<?php

namespace Architecture\Application\Izin\Create;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\Izin\IzinBase;
use Architecture\Domain\Entity\JenisIzin;
use Architecture\Domain\ValueObject\Date;
use Architecture\Shared\TypeData;

class CreateIzinCommand extends Command
{
    use IzinBase;
    public function __construct(
        $nidn,
        $nip,
        Date $tanggal_pengajuan,
        $tujuan,
        ?JenisIzin $jenis_izin=null,
        $dokumen,
        $catatan,
        $status, 
        public TypeData $option = TypeData::Entity
    ) {
        $this->nidn = $nidn;
        $this->nip = $nip;
        $this->tanggal_pengajuan = $tanggal_pengajuan;
        $this->tujuan = $tujuan;
        $this->jenis_izin = $jenis_izin;
        $this->dokumen = $dokumen;
        $this->status = $status;
        $this->catatan = $catatan;
    }
}