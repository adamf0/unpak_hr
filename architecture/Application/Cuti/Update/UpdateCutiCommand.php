<?php

namespace Architecture\Application\Cuti\Update;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\Cuti\CutiBase;
use Architecture\Domain\Entity\Dosen;
use Architecture\Domain\Entity\JenisCuti;
use Architecture\Domain\Entity\Pegawai;
use Architecture\Domain\ValueObject\Date;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class UpdateCutiCommand extends Command
{
    use IdentityCommand,CutiBase;
    public function __construct(
        $id,
        ?Dosen $dosen=null,
        ?Pegawai $pegawai=null,
        ?JenisCuti $jenis_cuti=null,
        $lama_cuti,
        Date $tanggal_mulai,
        ?Date $tanggal_akhir=null,
        $tujuan,$dokumen,
        ?Pegawai $verifikasi=null,
        $status, 
        public TypeData $option = TypeData::Entity
    ) {
        $this->id = $id;
        $this->dosen = $dosen;
        $this->pegawai = $pegawai;
        $this->jenis_cuti = $jenis_cuti;
        $this->lama_cuti = $lama_cuti;
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->tujuan = $tujuan;
        $this->dokumen = $dokumen;
        $this->verifikasi = $verifikasi;
        $this->status = $status;
    }
}