<?php

namespace Architecture\Application\Cuti\Update;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Domain\Enum\FormatDate;
use Architecture\External\Persistance\ORM\Cuti as ModelCuti;

class UpdateCutiCommandHandler extends CommandHandler
{
    public function handle(UpdateCutiCommand $command)
    {
        $Cuti = ModelCuti::findOrFail($command->GetId());
        $Cuti->nidn = $command->GetDosen()?->GetNidn();
        $Cuti->nip = $command->GetPegawai()?->GetNip();
        $Cuti->id_jenis_cuti = $command->GetJenisCuti()->GetId();
        $Cuti->lama_cuti = $command->GetLamaCuti();
        $Cuti->tanggal_mulai = $command->GetTanggalMulai()->toFormat(FormatDate::Default);
        $Cuti->tanggal_akhir = $command->GetTanggalAkhir()?->toFormat(FormatDate::Default);
        $Cuti->tujuan = $command->GetTujuan();
        $Cuti->dokumen = $command->GetDokumen();
        $Cuti->status = $command->GetStatus();
        if($Cuti->isDirty()) $Cuti->saveOrFail();
    }
}