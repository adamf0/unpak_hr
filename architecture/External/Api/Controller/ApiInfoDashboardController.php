<?php

namespace Architecture\External\Api\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Cuti\List\GetAllCutiByNIDNQuery;
use Architecture\Application\Cuti\List\GetAllCutiByNIPQuery;
use Architecture\Application\Izin\List\GetAllIzinByNIDNQuery;
use Architecture\Application\Izin\List\GetAllIzinByNIPQuery;
use Architecture\Application\Presensi\List\GetAllPresensiByNIDNQuery;
use Architecture\Application\Presensi\List\GetAllPresensiByNIPQuery;
use Architecture\Application\SPPD\List\GetAllSPPDByNIDNQuery;
use Architecture\Application\SPPD\List\GetAllSPPDByNIPQuery;
use Architecture\Domain\ValueObject\Date;
use Carbon\Carbon;
use Exception;

use function PHPUnit\Framework\isEmpty;

class ApiInfoDashboardController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function isLate($p){
        return $p->GetAbsenMasuk()->isGreater(new Date($p->GetTanggal()->val()->setTimeFromTimeString("08:01:00")));
    }
    public function is8Hour($p){
        if(!$this->isLate($p)) 
            return $p->GetAbsenKeluar()==null? false:$p->GetAbsenKeluar()->isGreater(new Date($p->GetTanggal()->val()->setTimeFromTimeString("14:59:00")));
        else if($this->isLate($p))
            return $p->GetAbsenKeluar()==null? false:$p->GetAbsenKeluar()->isGreater(new Date($p->GetAbsenMasuk()->val()->addHour(8)));
        else 
            return false;
    }

    public function index($type,$id){
        try {
            $presensi = $this->queryBus->ask(
                $type=="nidn"? new GetAllPresensiByNIDNQuery($id):new GetAllPresensiByNIPQuery($id)
            );
            $list_presensi = $presensi->reduce(function ($carry, $item){
                if($item->GetAbsenMasuk() instanceof Date){
                    $carry[] = $item;   
                }

                return $carry;
            });
            $list_tidak_masuk = $presensi->reduce(function ($carry, $item){ //
                $dateNow = Carbon::now()->format('Y-m-d');
                if( isEmpty($item->GetAbsenMasuk() && $item->GetTanggal()->isLess(new Date($dateNow))) ){
                    $carry[] = $item;   
                }

                return $carry;
            });
            $list_belum_absen = $presensi->reduce(function ($carry, $item){ //
                $dateNow = Carbon::now()->format('Y-m-d');
                if( isEmpty($item->GetAbsenMasuk() && !$item->GetTanggal()->isLess(new Date($dateNow))) ){
                    $carry[] = $item;   
                }

                return $carry;
            });
            
            $cuti = $this->queryBus->ask(
                $type=="nidn"? new GetAllCutiByNIDNQuery($id):new GetAllCutiByNIPQuery($id)
            );
            $izin = $this->queryBus->ask(
                $type=="nidn"? new GetAllIzinByNIDNQuery($id):new GetAllIzinByNIPQuery($id)
            );
            $sppd = $this->queryBus->ask(
                $type=="nidn"? new GetAllSPPDByNIDNQuery($id):new GetAllSPPDByNIPQuery($id)
            );

            return response()->json([
                "status"=>"ok",
                "message"=>"",
                "data"=>(object)[
                    "presensi"=>[
                        "total"=>($presensi??collect([]))->count(),
                        "tepat"=>collect($list_presensi)->filter(fn($p)=>!$this->isLate($p))->count(),
                        "telat"=>collect($list_presensi)->filter(fn($p)=>$this->isLate($p))->count(),
                        "l8"=>collect($list_presensi)->filter(fn($p)=>!$this->is8Hour($p))->count(),
                        "r8"=>collect($list_presensi)->filter(fn($p)=>$this->is8Hour($p))->count(),
                        "tidak_masuk"=>collect($list_tidak_masuk)->count(),
                        "belum_absen"=>collect($list_belum_absen)->count(),
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
