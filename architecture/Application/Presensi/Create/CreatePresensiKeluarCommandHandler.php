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
        $absen_keluar = $command->GetAbsenKeluar()->toFormat(FormatDate::YMDHIS);

        $Absensi = Absensi::where('tanggal', $tanggal);
        if($command->GetNIDN()!=null){
            $Absensi->where('nidn',$command->GetNIDN());    
        } else if($command->GetNIP()!=null){
            $Absensi->where('nip',$command->GetNIP());
        }
        $Absensi->absen_keluar = $absen_keluar;
        $Absensi->catatan_pulang = $command->GetCatatanPulang();
        $Absensi->saveOrFail();
    }
}