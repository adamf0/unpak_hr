<?php

namespace Architecture\Application\Izin\Create;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Domain\Enum\FormatDate;
use Architecture\External\Persistance\ORM\Izin as ModelIzin;

class CreateIzinCommandHandler extends CommandHandler
{
    public function handle(CreateIzinCommand $command)
    {
        $IzinBaru = new ModelIzin();
        $IzinBaru->nidn = $command->GetDosen()?->GetNidn();
        $IzinBaru->nip = $command->GetPegawai()?->GetNip();
        $IzinBaru->tanggal_pengajuan = $command->GetTanggalPengajuan()->toFormat(FormatDate::Default);
        $IzinBaru->tujuan = $command->GetTujuan();
        $IzinBaru->id_jenis_izin = $command->GetJenisIzin()?->GetId();
        $IzinBaru->dokumen = $command->GetDokumen();
        $IzinBaru->verifikasi = $command->GetVerifikasi()?->GetNip();
        $IzinBaru->status = $command->GetStatus();
        $IzinBaru->saveOrFail();
    }
}