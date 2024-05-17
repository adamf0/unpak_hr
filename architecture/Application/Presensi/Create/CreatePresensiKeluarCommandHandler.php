<?php

namespace Architecture\Application\Presensi\Create;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Domain\Enum\FormatDate;
use Architecture\External\Persistance\ORM\Absensi;

class CreatePresensiKeluarCommandHandler extends CommandHandler
{
    public function handle(CreatePresensiKeluarCommand $command)
    {
        $tanggal = $command->GetTanggal()->toFormat(FormatDate::Default);

        $Absensi = Absensi::where('tanggal', $tanggal)->findOrFail();
        $Absensi->nidn = $command->GetNIDN();
        $Absensi->nip = $command->GetNIP();
        $Absensi->absen_keluar = $tanggal." ".$command->GetAbsenKeluar();
        $Absensi->catatan_pulang = $command->GetCatatanPulang();
        $Absensi->saveOrFail();
    }
}