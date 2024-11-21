<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Presensi\FirstData\GetPresensiByNIDNQuery;
use Architecture\Application\Presensi\FirstData\GetPresensiByNIPQuery;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\External\Persistance\ORM\Cuti;
use Architecture\External\Persistance\ORM\JenisCuti;
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
            $laporan_cuti = collect([]);

            if(!is_null(Session::get('nidn'))){
                $presensi = $this->queryBus->ask(new GetPresensiByNIDNQuery(Session::get('nidn')));
                $jenis_cuti = JenisCuti::where("id","1")->get();
                foreach($jenis_cuti as $jc){
                    $total_cuti = Cuti::select("lama_cuti")
                                    ->where('nidn',Session::get('nidn'))
                                    ->where(DB::raw("YEAR(tanggal_mulai)"),DB::raw("YEAR(now())"))
                                    ->where("id_jenis_cuti",$jc->id)
                                    ->where("status","terima sdm")
                                    ->get()
                                    ->pluck("lama_cuti")
                                    ->sum()??0;

                    $sisa_cuti_tahun = $jc->max - $total_cuti;
                    $jc->sisa_cuti = $sisa_cuti_tahun<0? 0:$sisa_cuti_tahun;
                }
                
                return view('dashboard.index',[
                    "presensi"=>$presensi,
                    "laporan_cuti"=>$jenis_cuti,
                ]);
            } else if(!is_null(Session::get('nip'))){
                $presensi = $this->queryBus->ask(new GetPresensiByNIPQuery(Session::get('nip')));
                $jenis_cuti = JenisCuti::where("id","1")->get();
                foreach($jenis_cuti as $jc){
                    $total_cuti = Cuti::select("lama_cuti")
                                    ->where('nip',Session::get('nip'))
                                    ->where(DB::raw("YEAR(tanggal_mulai)"),DB::raw("YEAR(now())"))
                                    ->where("id_jenis_cuti",$jc->id)
                                    ->where("status","terima sdm")
                                    ->get()
                                    ->pluck("lama_cuti")
                                    ->sum()??0;

                    $sisa_cuti_tahun = $jc->max - $total_cuti;
                    $jc->sisa_cuti = $sisa_cuti_tahun<0? 0:$sisa_cuti_tahun;
                }

                return view('dashboard.index',[
                    "presensi"=>$presensi,
                    "laporan_cuti"=>$jenis_cuti,
                ]);
            }

            return view('dashboard.index',[
                "presensi"=>$presensi,
                "laporan_cuti"=>$laporan_cuti,
            ]);
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            throw $e;
        }
    }
}
