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
        $SPPD->nidn = $command->GetDosen()?->GetNidn();
        $SPPD->nip = $command->GetPegawai()?->GetNip();
        $SPPD->id_jenis_sppd = $command->GetJenisSPPD()?->GetId();
        $SPPD->tanggal_berangkat = $command->GetTanggalBerangkat()?->toFormat(FormatDate::Default);
        $SPPD->tanggal_kembali = $command->GetTanggalKembali()?->toFormat(FormatDate::Default);
        $SPPD->tujuan = $command->GetTujuan();
        $SPPD->keterangan = $command->GetKeterangan();
        $SPPD->sarana_transportasi = $command->GetSaranaTransportasi();
        $SPPD->verifikasi = $command->GetVerifikasi()?->GetNip();
        $SPPD->status = $command->GetStatus();
        if($SPPD->isDirty()) $SPPD->saveOrFail();

        return $SPPD;
    }
}