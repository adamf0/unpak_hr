<?php

namespace Architecture\External\Api\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Cuti\List\GetAllCutiByNIDNQuery;
use Architecture\Application\Cuti\List\GetAllCutiByNIPQuery;
use Architecture\Application\Izin\List\GetAllIzinByNIDNQuery;
use Architecture\Application\Izin\List\GetAllIzinByNIPQuery;
use Architecture\Application\KlaimAbsen\List\GetAllKlaimAbsenQuery;
use Architecture\Application\Presensi\List\GetAllPresensiByNIDNQuery;
use Architecture\Application\Presensi\List\GetAllPresensiByNIPQuery;
use Architecture\Application\SPPD\List\GetAllSPPDByNIDNQuery;
use Architecture\Application\SPPD\List\GetAllSPPDByNIPQuery;
use Architecture\Domain\ValueObject\Date;
use Architecture\Shared\TypeData;
use Carbon\Carbon;
use Exception;
use Architecture\Shared\Facades\Utility;

class ApiInfoDashboardController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function index($type,$id){
        try {
            $presensi = $this->queryBus->ask(
                $type=="nidn"? new GetAllPresensiByNIDNQuery($id,date('Y-m'),TypeData::Default):new GetAllPresensiByNIPQuery($id,date('Y-m'),TypeData::Default)
            );
            $cuti = $this->queryBus->ask(
                $type=="nidn"? new GetAllCutiByNIDNQuery($id):new GetAllCutiByNIPQuery($id)
            );
            $izin = $this->queryBus->ask(
                $type=="nidn"? new GetAllIzinByNIDNQuery($id):new GetAllIzinByNIPQuery($id)
            );
            $sppd = $this->queryBus->ask(
                $type=="nidn"? new GetAllSPPDByNIDNQuery($id):new GetAllSPPDByNIPQuery($id)
            );

            $list_klaim_absen   = $this->queryBus->ask(new GetAllKlaimAbsenQuery($type=="nidn"? $id:null,$type!="nidn"? $id:null,date('Y'),TypeData::Default));

            $tepat      =0;
            $telat      =0;
            $l8         =0;
            $r8         =0;
            $tidak_masuk=0;
            $belum_absen=0;
            $total_libur=0;
            $presensi->each(function ($item) use($list_klaim_absen,&$tepat,&$telat,&$l8,&$r8,&$tidak_masuk,&$belum_absen,&$total_libur){
                $klaim = $list_klaim_absen->where('status','terima')->where('Presensi.tanggal',$item->tanggal);
                $klaim = $klaim->count()==1? $klaim[0]:null;
                $masuk = $klaim?->jam_masuk??$item->absen_masuk;
                $keluar = $klaim?->jam_keluar??$item->absen_keluar;

                $tgl = Carbon::parse($item->tanggal)->setTimezone('Asia/Jakarta');
                if($tgl->isSunday()){
                    $total_libur += 1;
                }
                $rule_belum_absen = empty($masuk) && !$tgl->isSunday() && $tgl->format('Y-m-d')==Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
                $rule_tidak_masuk = empty($masuk) && !$tgl->isSunday() && $tgl->lessThanOrEqualTo(Carbon::now()->setTimezone('Asia/Jakarta')->subDay());
                
                if($rule_belum_absen){
                    $belum_absen += 1;
                }
                if($rule_tidak_masuk){
                    $tidak_masuk += 1;
                }
                if(!empty($masuk)){
                    $tepat += !Utility::isLate($masuk, $item->tanggal)? 1:0;
                    $telat += Utility::isLate($masuk, $item->tanggal)? 1:1;
                }
                if(!empty($masuk) && !empty($keluar)){
                    $l8 += !Utility::is8Hour($item->tanggal, $masuk, $keluar)? 1:0;
                    $r8 += Utility::is8Hour($item->tanggal, $masuk, $keluar)? 1:0;
                }
            });

            return response()->json([
                "status"=>"ok",
                "message"=>"",
                "data"=>(object)[
                    "presensi"=>[
                        "total"=>($presensi??collect([]))->count() - $total_libur,
                        "tepat"=>$tepat,
                        "telat"=>$telat,
                        "l8"=>$l8,
                        "r8"=>$r8,
                        "tidak_masuk"=>$tidak_masuk,
                        "belum_absen"=>$belum_absen,
                    ],
                    "cuti"=>[
                        "total"=>$cuti->count(),
                        "tolak"=>$cuti->filter(fn($c)=>$c->GetStatus()=="tolak")->count(),
                        "tunggu"=>$cuti->filter(fn($c)=>$c->GetStatus()=="menunggu")->count(),
                    ],
                    "izin"=>[
                        "total"=>$izin->count(),
                        "tolak"=>$izin->filter(fn($c)=>$c->GetStatus()=="tolak")->count(),
                        "tunggu"=>$izin->filter(fn($c)=>$c->GetStatus()=="menunggu")->count(),
                    ],
                    "sppd"=>[
                        "total"=>$sppd->count(),
                        "tolak"=>$sppd->filter(fn($c)=>$c->GetStatus()=="tolak")->count(),
                        "tunggu"=>$sppd->filter(fn($c)=>$c->GetStatus()=="menunggu")->count(),
                    ],
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status"=>"fail",
                "message"=>"data tidak ditemukan",
                "data"=>null,
                "log"=>$e->getMessage()
            ]);
        }
    }
}
