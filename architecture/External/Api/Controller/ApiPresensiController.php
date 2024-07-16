<?php

namespace Architecture\External\Api\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Presensi\Create\CreatePresensiKeluarCommand;
use Architecture\Application\Presensi\Create\CreatePresensiMasukCommand;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\DosenReferensi;
use Architecture\Domain\Entity\PegawaiReferensi;
use Architecture\Domain\RuleValidationRequest\Presensi\CreatePresensiRuleReq;
use Architecture\Domain\ValueObject\Date;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApiPresensiController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function index(Request $request){
        try {
            $validator      = validator($request->all(), CreatePresensiRuleReq::create());

            if(count($validator->errors())){
                return response()->json([
                    "status"=>"fail",
                    "message"=>"data absensi tidak valid",
                    "data"=>$request->all(),
                    "log"=>$validator->errors()->toArray()
                ]);
            } 

            $nidn = empty($request?->nidn)? null:$request?->nidn;
            $nip = empty($request?->nip)? null:$request?->nip;
            $key = match(true){
                !empty($nidn) => $nidn,
                !empty($nip) => $nip,
                default => "#",
            };

            if($request->type=="absen_masuk"){
                $this->commandBus->dispatch(new CreatePresensiMasukCommand(
                    Creator::buildDosen(DosenReferensi::make($nidn)),
                    Creator::buildPegawai(PegawaiReferensi::make($nip)),
                    new Date($request->tanggal),
                    new Date($request->absen_masuk),
                    $request->catatan_telat,
                ));
                Cache::delete("kalender-$key");

                return response()->json([
                    "status"=>"ok",
                    "message"=>"anda berhasil absen masuk",
                    "data"=>$request->all(),
                ]);
            } else if($request->type=="absen_keluar"){
                $this->commandBus->dispatch(new CreatePresensiKeluarCommand(
                    Creator::buildDosen(DosenReferensi::make($nidn)),
                    Creator::buildPegawai(PegawaiReferensi::make($nip)),
                    new Date($request->tanggal),
                    new Date($request->absen_keluar),
                    $request->catatan_pulang,
                ));
                Cache::delete("kalender-$key");

                return response()->json([
                    "status"=>"ok",
                    "message"=>"anda berhasil absen keluar",
                    "data"=>$request->all(),
                ]);
            } else{
                throw new Exception('invalid command');
            }
        } catch (Exception $e) {
            return response()->json([
                "status"=>"fail",
                "message"=>"data tidak ditemukan",
                "data"=>$request->all(),
                "log"=>$e->getMessage()
            ]);
        }
    }
}
