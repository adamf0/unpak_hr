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
        $absen_masuk = $command->GetAbsenMasuk()->toFormat(FormatDate::YMDHIS);

        $Absensi = Absensi::where('tanggal', $tanggal);
        if($command->GetNIDN()!=null){
            $Absensi->where('nidn',$command->GetNIDN());    
        } else if($command->GetNIP()!=null){
            $Absensi->where('nip',$command->GetNIP());
        }
        $Absensi = $Absensi->firstOrFail();
        $Absensi->absen_masuk = $absen_masuk;
        $Absensi->catatan_telat = $command->GetCatatanTelat();
        $Absensi->saveOrFail();
    }
}