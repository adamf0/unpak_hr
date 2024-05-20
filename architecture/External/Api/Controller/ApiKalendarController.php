<?php

namespace Architecture\External\Api\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Cuti\List\GetAllCutiQuery;
use Architecture\Application\Izin\List\GetAllIzinQuery;
use Architecture\Application\MasterKalendar\List\GetAllMasterKalendarQuery;
use Architecture\Application\Presensi\List\GetAllPresensiQuery;
use Architecture\Application\SPPD\List\GetAllSPPDQuery;
use Architecture\Domain\ValueObject\Date;
use Architecture\Shared\TypeData;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ApiKalendarController extends Controller //data cuti, izin, sppd, absen belum masuk
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}

    public function isLate($jam_masuk=null,$jam_keluar=null){
        $jamMasuk = new Date($jam_masuk);
        $jamKeluar = new Date($jam_keluar." 08:01:00");
        return $jamMasuk->isGreater($jamKeluar);
    }
    public function is8Hour($jam_masuk=null,$jam_keluar=null){
        $jamMasuk = new Date($jam_masuk);
        $jamKeluar = new Date($jam_keluar);

        if(!empty($jamKeluar) && !$this->isLate($jamMasuk,$jamKeluar)){
            $jamKeluar = new Date($jam_keluar." 14:59:00");
            return $jamKeluar->isGreater($jamKeluar);
        } 
        else if(!empty($jamKeluar) && $this->isLate($jamMasuk,$jamKeluar)){
            $jamKeluar = new Date(Carbon::parse($jam_keluar)->addHour(8)->toISOString());
            return $jamKeluar->isGreater($jamKeluar);
        }
        else 
            return false;
    }
    
    public function index(Request $request, $tahun, $format='default'){
        try {
            $nidn = $request->has('nidn')? $request->query('nidn'):null;
            $nip = $request->has('nip')? $request->query('nip'):null;
            $level = $request->has('level')? $request->query('level'):null;

            $list_cuti = in_array($level, ["dosen","pegawai"])? $this->queryBus->ask(new GetAllCutiQuery($nidn,$nip,$tahun,TypeData::Default)) : collect([]);
            $list_izin = in_array($level, ["dosen","pegawai"])? $this->queryBus->ask(new GetAllIzinQuery($nidn,$nip,$tahun,TypeData::Default)) : collect([]);
            $list_sppd = in_array($level, ["dosen","pegawai"])? $this->queryBus->ask(new GetAllSPPDQuery($nidn,$nip,$tahun,TypeData::Default)) : collect([]);
            $list_absen = in_array($level, ["dosen","pegawai"])? $this->queryBus->ask(new GetAllPresensiQuery($nidn,$nip,$tahun,TypeData::Default)) : collect([]);
            $master_kalendar = $this->queryBus->ask(new GetAllMasterKalendarQuery(1,1,$tahun,TypeData::Default));
            
            $listKalendar = $master_kalendar->reduce(function ($carry, $item) use ($format) {
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

            $listCuti = $list_cuti->reduce(function ($carry, $item) use ($format) {
                if($format=="full-calendar"){
                    $carry[] = [
                        "title"=>$item->tujuan??"NA",
                        "start"=>$item->tanggal_mulai,
                        "end"=>($item->tanggal_akhir == null || $item->tanggal_akhir == $item->tanggal_mulai)? $item->tanggal_mulai:$item->tanggal_akhir,
                        "backgroundColor"=>'#ffc107',
                        "borderColor"=>"transparent",
                        // "className"=>"bg-danger"
                    ];
                } else{
                    $start  = Carbon::parse($item->tanggal_mulai);
                    $end    = Carbon::parse($item->tanggal_akhir);
                    $days   = $end->diffInDays($start);
                    for ($i = 0; $i <= $days; $i++) {
                        $carry[] = [
                            "id"=>$item->id,
                            "tanggal"=>Carbon::parse($item->tanggal_mulai)->addDays($i)->format('Y-m-d'),
                            "keterangan"=>$item->tujuan??"NA",
                        ];
                    }
                }
                return $carry;
            }, []);

            $listIzin = $list_izin->reduce(function ($carry, $item) use ($format) {
                if($format=="full-calendar"){
                    $carry[] = [
                        "title"=>$item->tujuan??"NA",
                        "start"=>$item->tanggal_pengajuan,
                        "end"=>$item->tanggal_pengajuan,
                        "backgroundColor"=>'#0044cc',
                        "borderColor"=>"transparent",
                        // "className"=>"bg-danger"
                    ];
                } else{
                    $carry[] = [
                        "id"=>$item->id,
                        "tanggal"=>Carbon::parse($item->tanggal_pengajuan)->format('Y-m-d'),
                        "keterangan"=>$item->tujuan??"NA",
                    ];
                }
                return $carry;
            }, []);

            $listSPPD = $list_sppd->reduce(function ($carry, $item) use ($format) {
                if($format=="full-calendar"){
                    $carry[] = [
                        "title"=>$item->keterangan??"NA",
                        "start"=>$item->tanggal_berangkat,
                        "end"=>$item->tanggal_kembali,
                        "backgroundColor"=>'#0dcaf0',
                        "borderColor"=>"transparent",
                        // "className"=>"bg-danger"
                    ];
                } else{
                    $carry[] = [
                        "id"=>$item->id,
                        "tanggal"=>Carbon::parse($item->tanggal_pengajuan)->format('Y-m-d'),
                        "keterangan"=>$item->keterangan??"NA",
                    ];
                }
                return $carry;
            }, []);

            // $listTidakMasuk = $list_absen->reduce(function ($carry, $item){ //
            //     $dateNow = Carbon::now()->format('Y-m-d');
            //     if( isEmpty($item->GetAbsenMasuk() && $item->GetTanggal()->isLess(new Date($dateNow))) ){
            //         $carry[] = $item;   
            //     }

            //     return $carry;
            // });
            $listAbsen = $list_absen->reduce(function ($carry, $item) use ($format) {
                if($format=="full-calendar"){
                    $background = match(true){
                        empty($item->absen_masuk) && empty($item->absen_keluar) => "dc3545", //tidak masuk
                        !empty($item->absen_masuk) => "198754", //masuk
                        default => "#000"
                    };
                    $title = match(true){
                        empty($item->absen_masuk) && empty($item->absen_keluar) => "tidak masuk", //tidak masuk
                        !empty($item->absen_masuk) => Carbon::parse($item->absen_masuk)->format("H:m:s").(empty($item->absen_keluar)? "":" - ".Carbon::parse($item->absen_keluar)->format("H:m:s")), //masuk
                        default => "NA"
                    };
                    $carry[] = [
                        "title"=>$title,
                        "start"=>$item->tanggal,
                        "end"=>$item->tanggal,
                        "backgroundColor"=>$background,
                        "borderColor"=>"transparent",
                        // "className"=>"bg-danger"
                    ];
                } else{
                    $carry[] = [
                        "id"=>$item->id,
                        "tanggal"=>Carbon::parse($item->tanggal_pengajuan)->format('Y-m-d'),
                        "keterangan"=>$item->keterangan??"NA",
                    ];
                }
                return $carry;
            }, []);
            // dd($listAbsen);

            $list = array_merge($listKalendar,$listCuti,$listIzin,$listSPPD);

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
                "log"=>$e->getMessage()
            ]);
        }
    }
}
