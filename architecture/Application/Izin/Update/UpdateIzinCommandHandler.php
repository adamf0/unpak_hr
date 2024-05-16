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
        $Izin->nidn = $command->GetNIDN();
        $Izin->nip = $command->GetNIP();
        $Izin->tanggal_pengajuan = $command->GetTanggalPengajuan()->toFormat(FormatDate::Default);
        $Izin->tujuan = $command->GetTujuan();
        $Izin->id_jenis_izin = $command->GetJenisIzin()?->GetId();
        $Izin->dokumen = $command->GetDokumen();
        $Izin->status = $command->GetStatus();
        $Izin->catatan = $command->GetCatatan();
        if($Izin->isDirty()) $Izin->saveOrFail();
    }
}