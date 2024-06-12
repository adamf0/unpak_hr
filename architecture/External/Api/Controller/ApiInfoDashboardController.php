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
use Architecture\Application\Presensi\List\GetAllPresensiQuery;
use Architecture\Application\SPPD\List\GetAllSPPDByNIDNQuery;
use Architecture\Application\SPPD\List\GetAllSPPDByNIPQuery;
use Architecture\Domain\ValueObject\Date;
use Architecture\Shared\TypeData;
use Carbon\Carbon;
use Exception;

use function PHPUnit\Framework\isEmpty;

class ApiInfoDashboardController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function isLate($tanggal_jam_masuk=null,$tanggal=null){
        $masuk = new Date($tanggal_jam_masuk);
        $keluar = new Date($tanggal." 08:01:00");
        return $masuk->isGreater($keluar);
    }
    public function is8Hour($tanggal=null,$tanggal_jam_masuk=null,$tanggal_jam_keluar=null){
        if(!empty($keluar) && !$this->isLate($tanggal_jam_masuk,$tanggal)){
            $jam_pulang = "14:59:00";
            if (Carbon::now()->setTimezone('Asia/Jakarta')->dayOfWeek == Carbon::FRIDAY) {
                $jam_pulang = "13:59:00";
            } elseif (Carbon::now()->setTimezone('Asia/Jakarta')->dayOfWeek == Carbon::SATURDAY) {
                $jam_pulang = "11:59:00";
            }
            $aturanKeluar = new Date($tanggal." $jam_pulang");
            $keluar = new Date($tanggal_jam_keluar);
            return $keluar->isGreater($aturanKeluar);
        } 
        else if(!empty($keluar) && $this->isLate($tanggal_jam_masuk,$tanggal)){
            $keluar = new Date(Carbon::parse($tanggal_jam_keluar)->setTimezone('Asia/Jakarta')->addHour(8)->toISOString());
            return $keluar->isGreater($keluar);
        }
        else 
            return false;
    }

    public function index($type,$id){
        try {
            $presensi = $this->queryBus->ask(
                $type=="nidn"? new GetAllPresensiByNIDNQuery($id,TypeData::Default):new GetAllPresensiByNIPQuery($id,TypeData::Default)
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
            $presensi->each(function ($item) use($list_klaim_absen,&$tepat,&$telat,&$l8,&$r8,&$tidak_masuk,&$belum_absen){
                $klaim = $list_klaim_absen->where('status','terima')->where('Presensi.tanggal',$item->tanggal);
                $klaim = $klaim->count()==1? $klaim[0]:null;

                $rule_masuk = !is_null($klaim) || !empty($item->absen_masuk);
                $rule_belum_absen = is_null($klaim) && empty($item->absen_masuk) && Carbon::parse($item->tanggal)->setTimezone('Asia/Jakarta')->equalTo(Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d'));
                $rule_tidak_masuk = is_null($klaim) && empty($item->absen_masuk) && Carbon::parse($item->tanggal)->setTimezone('Asia/Jakarta')->lessThan(Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d'));
                
                if($rule_belum_absen){
                    $belum_absen++;
                }
                if($rule_tidak_masuk){
                    $tidak_masuk++;
                }
                if($rule_masuk){
                    if(!$this->isLate($item->absen_masuk, $item->tanggal)){
                        $tepat++;
                    } else{
                        $telat++;
                    }

                    if(!empty($item->absen_keluar)){
                        if(!$this->is8Hour($item->tanggal, $item->absen_masuk, $item->absen_keluar)){
                            $l8++;
                        } else{
                            $r8++;
                        }   
                    }
                }
            });

            return response()->json([
                "status"=>"ok",
                "message"=>"",
                "data"=>(object)[
                    "presensi"=>[
                        "total"=>($presensi??collect([]))->count(),
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
