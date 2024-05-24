<?php

namespace Architecture\Application\MasterKalendar\Create;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Domain\Enum\FormatDate;
use Architecture\External\Persistance\ORM\MasterKalendar as ModelMasterKalendar;

class CreateMasterKalendarCommandHandler extends CommandHandler
{
    public function handle(CreateMasterKalendarCommand $command)
    {
        $MasterKalendarBaru = new ModelMasterKalendar();
        $MasterKalendarBaru->tanggal_mulai = $command->GetTanggalMulai()->toFormat(FormatDate::Default);
        $MasterKalendarBaru->tanggal_berakhir = $command->GetTanggalAkhir()->toFormat(FormatDate::Default);
        $MasterKalendarBaru->keterangan = $command->GetKeterangan();
        $MasterKalendarBaru->saveOrFail();
    }
}