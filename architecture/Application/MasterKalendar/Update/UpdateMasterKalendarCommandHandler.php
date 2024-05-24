<?php

namespace Architecture\Application\MasterKalendar\Update;

use Architecture\Application\Abstractions\Messaging\CommandHandler;
use Architecture\Application\MasterKalendar\Update\UpdateMasterKalendarCommand;
use Architecture\Domain\Enum\FormatDate;
use Architecture\External\Persistance\ORM\MasterKalendar as ModelMasterKalendar;

class UpdateMasterKalendarCommandHandler extends CommandHandler
{
    public function handle(UpdateMasterKalendarCommand $command)
    {
        $MasterKalendar = ModelMasterKalendar::findOrFail($command->GetId());
        $MasterKalendar->tanggal_mulai = $command->GetTanggalMulai()->toFormat(FormatDate::Default);
        $MasterKalendar->tanggal_berakhir = $command->GetTanggalAkhir()->toFormat(FormatDate::Default);
        $MasterKalendar->keterangan = $command->GetKeterangan();
        if($MasterKalendar->isDirty()) $MasterKalendar->saveOrFail();
    }
}