<?php

namespace Architecture\External\Api\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Cuti\Update\ApprovalCutiCommand;
use Architecture\External\Persistance\ORM\SlipGaji;
use Exception;
use Illuminate\Http\Request;

class ApiSlipGajiController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    function namaBulan($number){
        return match((int) $number){
            1=>"Januari",
            2=>"Februari",
            3=>"Maret",
            4=>"April",
            5=>"Mei",
            6=>"Juni",
            7=>"Juli",
            8=>"Agustus",
            9=>"September",
            10=>"Oktober",
            11=>"November",
            12=>"Desember",
            default=>null,
        };
    }
    public function index(Request $request, $tahun=null, $bulan=null){
        try {
            if($tahun==null || $bulan==null){
                return response()->json([
                    "status"=>"fail",
                    "message"=>"tahun / bulan tidak boleh kosong",
                    "data"=>null,
                ]);
            }
            $bulan = $this->namaBulan($bulan);

            $nip = 10411006520; //aries
            $slip_gaji = SlipGaji::where('nip',$nip)->first();
            $slip_gaji->bulan=$bulan;

            return response()->json([
                "status"=>"ok",
                "message"=>null,
                "data"=>$slip_gaji,
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
