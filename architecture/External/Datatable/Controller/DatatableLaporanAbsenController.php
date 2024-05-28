<?php

namespace Architecture\External\Datatable\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\JenisIzin\List\GetAllJenisIzinQuery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables as DataTables;

class DatatableLaporanAbsenController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function index(Request $request){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time','-1');
        
        $start = Carbon::now()->setTimezone('Asia/Jakarta')->startOfMonth();
        $end = Carbon::now()->setTimezone('Asia/Jakarta')->endOfMonth();
        $list_tanggal = [];
        $listtgl = [];
        for ($date = Carbon::now()->setTimezone('Asia/Jakarta')->startOfMonth(); $date->lte($end); $date->addDay()) {
            $list_tanggal[] = $date->copy()->format('Y-m-d');
            $listtgl[] = $date->copy()->format('d');
        }
        $list_data = DB::table('laporan_merge_absen_izin_cuti')->whereBetween('tanggal',[$start->format('Y-m-d'),$end->format('Y-m-d')])->orderBy('tanggal')->get();
        
        $groupedData = collect($list_data)->groupBy(function ($item) {
            return $item->tanggal . $item->nidn . $item->nip;
        });
        $list_data = $groupedData->map(function ($group) {
            return $group->reduce(function ($carry, $item) {
                $info = json_decode($item->info, true);
                $carry['info'][] = $info;
                return $carry;
            }, [
                'nidn' => $group->first()->nidn,
                'nip' => $group->first()->nip,
                'tanggal' => $group->first()->tanggal,
                'info' => []
            ]);
        })->values();

        $table = DataTables::of($list_data)
        ->addIndexColumn()
        ->make(true);

        return $table;
    }
}
