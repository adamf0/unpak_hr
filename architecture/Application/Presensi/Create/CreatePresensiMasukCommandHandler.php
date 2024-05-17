<?php

namespace Architecture\Application\Presensi\Create;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Domain\Enum\FormatDate;
use Architecture\External\Persistance\ORM\Absensi;

class CreatePresensiMasukCommandHandler extends CommandHandler
{
    public function handle(CreatePresensiMasukCommand $command)
    {
        $tanggal = $command->GetTanggal()->toFormat(FormatDate::Default);

        $Absensi = Absensi::where('tanggal', $tanggal)->findOrFail();
        $Absensi->nidn = $command->GetNIDN();
        $Absensi->nip = $command->GetNIP();
        $Absensi->absen_masuk = $tanggal." ".$command->GetAbsenMasuk();
        $Absensi->catatan_telat = $command->GetCatatanTelat();
        $Absensi->saveOrFail();
    }
}