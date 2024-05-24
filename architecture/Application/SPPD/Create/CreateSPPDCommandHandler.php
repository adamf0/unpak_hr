<?php

namespace Architecture\Application\SPPD\Create;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Domain\Enum\FormatDate;
use Architecture\External\Persistance\ORM\SPPD as ModelSPPD;

class CreateSPPDCommandHandler extends CommandHandler
{
    public function handle(CreateSPPDCommand $command)
    {
        $SPPDBaru = new ModelSPPD();
        $SPPDBaru->nidn = $command->GetNIDN();
        $SPPDBaru->nip = $command->GetNIP();
        $SPPDBaru->id_jenis_sppd = $command->GetJenisSPPD()?->GetId();
        $SPPDBaru->tanggal_berangkat = $command->GetTanggalBerangkat()?->toFormat(FormatDate::Default);
        $SPPDBaru->tanggal_kembali = $command->GetTanggalKembali()?->toFormat(FormatDate::Default);
        $SPPDBaru->tujuan = $command->GetTujuan();
        $SPPDBaru->keterangan = $command->GetKeterangan();
        $SPPDBaru->status = $command->GetStatus();
        $SPPDBaru->saveOrFail();
        
        return $SPPDBaru;
    }
}