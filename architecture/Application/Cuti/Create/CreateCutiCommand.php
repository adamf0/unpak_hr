<?php

namespace Architecture\Application\Cuti\Create;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\Cuti\CutiBase;
use Architecture\Domain\Entity\JenisCuti;
use Architecture\Domain\ValueObject\Date;
use Architecture\Shared\TypeData;

class CreateCutiCommand extends Command
{
    use CutiBase;
    public function __construct($nidn,$nip,?JenisCuti $jenis_cuti=null,$lama_cuti,Date $tanggal_mulai,?Date $tanggal_akhir=null,$tujuan,$dokumen,$catatan,$status, public TypeData $option = TypeData::Entity) {
        $this->nidn = $nidn;
        $this->nip = $nip;
        $this->jenis_cuti = $jenis_cuti;
        $this->lama_cuti = $lama_cuti;
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->tujuan = $tujuan;
        $this->dokumen = $dokumen;
        $this->catatan = $catatan;
        $this->status = $status;
    }
}