<?php

namespace Architecture\Application\KlaimAbsen\Create;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\KlaimAbsen as ModelKlaimAbsen;

class CreateKlaimAbsenCommandHandler extends CommandHandler
{
    public function handle(CreateKlaimAbsenCommand $command)
    {
        $KlaimAbsenBaru = new ModelKlaimAbsen();
        $KlaimAbsenBaru->nidn = $command->GetDosen()?->GetNidn();
        $KlaimAbsenBaru->nip = $command->GetPegawai()?->GetNip();
        $KlaimAbsenBaru->id_presensi = $command->GetPresensi()->GetId();
        $KlaimAbsenBaru->jam_masuk = $command->GetJamMasuk();
        $KlaimAbsenBaru->jam_keluar = $command->GetJamKeluar();
        $KlaimAbsenBaru->tujuan = $command->GetTujuan();
        $KlaimAbsenBaru->dokumen = $command->GetDokumen();
        $KlaimAbsenBaru->saveOrFail();
    }
}