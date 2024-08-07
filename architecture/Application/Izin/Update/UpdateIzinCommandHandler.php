<?php

namespace Architecture\Application\Izin\Update;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Application\Izin\Update\UpdateIzinCommand;
use Architecture\Domain\Enum\FormatDate;
use Architecture\External\Persistance\ORM\Izin as ModelIzin;

class UpdateIzinCommandHandler extends CommandHandler
{
    public function handle(UpdateIzinCommand $command)
    {
        $Izin = ModelIzin::findOrFail($command->GetId());
        $Izin->nidn = $command->GetDosen()?->GetNidn();
        $Izin->nip = $command->GetPegawai()?->GetNip();
        $Izin->tanggal_pengajuan = $command->GetTanggalPengajuan()->toFormat(FormatDate::Default);
        $Izin->tujuan = $command->GetTujuan();
        $Izin->id_jenis_izin = $command->GetJenisIzin()?->GetId();
        $Izin->dokumen = $command->GetDokumen();
        $Izin->verifikasi = $command->GetVerifikasi()?->GetNip();
        $Izin->status = $command->GetStatus();
        if($Izin->isDirty()) $Izin->saveOrFail();
    }
}