<?php

namespace Architecture\Application\Cuti\Create;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Domain\Enum\FormatDate;
use Architecture\External\Persistance\ORM\Cuti as ModelCuti;

class CreateCutiCommandHandler extends CommandHandler
{
    public function handle(CreateCutiCommand $command)
    {
        $CutiBaru = new ModelCuti();
        $CutiBaru->nidn = $command->GetDosen()?->GetNidn();
        $CutiBaru->nip = $command->GetPegawai()?->GetNip();
        $CutiBaru->id_jenis_cuti = $command->GetJenisCuti()->GetId();
        $CutiBaru->lama_cuti = $command->GetLamaCuti();
        $CutiBaru->tanggal_mulai = $command->GetTanggalMulai()->toFormat(FormatDate::Default);
        $CutiBaru->tanggal_akhir = $command->GetTanggalAkhir()?->toFormat(FormatDate::Default);
        $CutiBaru->tujuan = $command->GetTujuan();
        $CutiBaru->dokumen = $command->GetDokumen();
        $CutiBaru->verifikasi = $command->GetVerifikasi()?->GetNip();
        $CutiBaru->status = $command->GetStatus();
        $CutiBaru->saveOrFail();
    }
}