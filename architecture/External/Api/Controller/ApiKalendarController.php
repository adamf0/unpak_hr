<?php

namespace Architecture\External\Api\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\MasterKalendar\List\GetAllMasterKalendarQuery;
use Architecture\Shared\TypeData;
use Carbon\Carbon;
use Exception;

use function PHPUnit\Framework\isNull;

class ApiKalendarController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function index($tahun, $format='default'){
        try {
            $master_kalendar = $this->queryBus->ask(new GetAllMasterKalendarQuery($tahun,1,1,TypeData::Default));
            $list            = $master_kalendar->reduce(function ($carry, $item) use ($format) {
                if($format=="full-calendar"){
                    $carry[] = [
                        "title"=>$item->keterangan??"NA",
                        "start"=>$item->tanggal_mulai,
                        "end"=>($item->tanggal_berakhir == null || $item->tanggal_berakhir == $item->tanggal_mulai)? $item->tanggal_mulai:$item->tanggal_berakhir,
                        "backgroundColor"=>'#dc3545',
                        "borderColor"=>"transparent",
                        // "className"=>"bg-danger"
                    ];
                } else{
                    $start  = Carbon::parse($item->tanggal_mulai);
                    $end    = Carbon::parse($item->tanggal_berakhir);
                    $days   = $end->diffInDays($start);
                    for ($i = 0; $i <= $days; $i++) {
                        $carry[] = [
                            "id"=>$item->id,
                            "tanggal"=>Carbon::parse($item->tanggal_mulai)->addDays($i)->format('Y-m-d'),
                            "keterangan"=>$item->keterangan??"NA",
                        ];
                    }
                }
                return $carry;
            }, []);

            return response()->json([
                "status"=>"ok",
                "message"=>"",
                "data"=>$list,
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status"=>"fail",
                "message"=>"data tidak ditemukan",
                "data"=>null,
                ""=>$e->getMessage()
            ]);
        }
    }
}
