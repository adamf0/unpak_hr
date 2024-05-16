<?php

namespace Architecture\Application\SPPD\Update;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Domain\Enum\FormatDate;
use Architecture\External\Persistance\ORM\SPPD as ModelSPPD;

class UpdateSPPDCommandHandler extends CommandHandler
{
    public function handle(UpdateSPPDCommand $command)
    {
        $SPPD = ModelSPPD::findOrFail($command->GetId());
        $SPPD->id = $command->GetId();
        $SPPD->nidn = $command->GetNIDN();
        $SPPD->nip = $command->GetNIP();
        $SPPD->id_jenis_sppd = $command->GetJenisSPPD()?->GetId();
        $SPPD->tanggal_berangkat = $command->GetTanggalBerangkat()?->toFormat(FormatDate::Default);
        $SPPD->tanggal_kembali = $command->GetTanggalKembali()?->toFormat(FormatDate::Default);
        $SPPD->tujuan = $command->GetTujuan();
        $SPPD->keterangan = $command->GetKeterangan();
        $SPPD->status = $command->GetStatus();
        if($SPPD->isDirty()) $SPPD->saveOrFail();
    }
}