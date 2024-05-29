<?php

namespace Architecture\Application\KlaimAbsen\Update;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\External\Persistance\ORM\KlaimAbsen as ModelKlaimAbsen;

class UpdateKlaimAbsenCommandHandler extends CommandHandler
{
    public function handle(UpdateKlaimAbsenCommand $command)
    {
        $KlaimAbsen = ModelKlaimAbsen::findOrFail($command->GetId());
        $KlaimAbsen->nidn = $command->GetDosen()?->GetNidn();
        $KlaimAbsen->nip = $command->GetPegawai()?->GetNip();
        $KlaimAbsen->id_presensi = $command->GetPresensi()->GetId();
        $KlaimAbsen->jam_masuk = $command->GetJamMasuk();
        $KlaimAbsen->jam_keluar = $command->GetJamKeluar();
        $KlaimAbsen->tujuan = $command->GetTujuan();
        $KlaimAbsen->dokumen = $command->GetDokumen();
        if($KlaimAbsen->isDirty()) $KlaimAbsen->saveOrFail();
    }
}