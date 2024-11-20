<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Presensi\FirstData\GetPresensiByNIDNQuery;
use Architecture\Application\Presensi\FirstData\GetPresensiByNIPQuery;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\External\Persistance\ORM\Cuti;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function Index(){
        try {
            $presensi = null;
            $sisa_cuti_tahun = 0;

            if(!is_null(Session::get('nidn'))){
                $presensi = $this->queryBus->ask(new GetPresensiByNIDNQuery(Session::get('nidn')));
                $total_cuti = DB::table("laporan_cuti_tahunan")
                                        ->where('nidn',Session::get('nidn'))
                                        ->where("tahun_cuti",date("Y"))
                                        ->first()?->total_cuti??0;
                $sisa_cuti_tahun = 12 - $total_cuti;

                return view('dashboard.index',[
                    "presensi"=>$presensi
                ]);
            } else if(!is_null(Session::get('nip'))){
                $presensi = $this->queryBus->ask(new GetPresensiByNIPQuery(Session::get('nip')));
                $total_cuti = DB::table("laporan_cuti_tahunan")
                                        ->where('nip',Session::get('nip'))
                                        ->where("tahun_cuti",date("Y"))
                                        ->first()?->total_cuti??0;
                $sisa_cuti_tahun = 12 - $total_cuti;

                return view('dashboard.index',[
                    "presensi"=>$presensi
                ]);
            }

            return view('dashboard.index',[
                "presensi"=>$presensi,
                "sisa_cuti_tahun"=>$sisa_cuti_tahun,
            ]);
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            throw $e;
        }
    }
}
