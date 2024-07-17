<?php

namespace Architecture\External\Api\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Cuti\List\GetAllCutiQuery;
use Architecture\Application\Izin\List\GetAllIzinQuery;
use Architecture\Application\KlaimAbsen\List\GetAllKlaimAbsenQuery;
use Architecture\Application\MasterKalendar\List\GetAllMasterKalendarQuery;
use Architecture\Application\Presensi\List\GetAllPresensiQuery;
use Architecture\Application\SPPD\List\GetAllSPPDQuery;
use Architecture\Domain\Behavioral\AbsenContext;
use Architecture\Domain\Behavioral\AbsenStrategy;
use Architecture\Domain\Behavioral\DefaultStrategy;
use Architecture\Domain\Behavioral\TidakAbsenStrategy;
use Architecture\Shared\TypeData;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Architecture\Shared\Facades\Utility;

class ApiKalendarController extends Controller //data cuti, izin, sppd, absen belum masuk
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {
    }

    public function index(Request $request, $tahun, $format = 'default')
    {
        try {
            $nidn = $request->has('nidn') ? $request->query('nidn') : null;
            $nip = $request->has('nip') ? $request->query('nip') : null;
            $level = $request->has('level') ? $request->query('level') : null;

            $list_cuti = in_array($level, ["dosen", "pegawai"]) ? $this->queryBus->ask(new GetAllCutiQuery($nidn, $nip, $tahun, TypeData::Default, false)) : collect([]);
            $list_izin = in_array($level, ["dosen", "pegawai"]) ? $this->queryBus->ask(new GetAllIzinQuery($nidn, $nip, $tahun, TypeData::Default, false)) : collect([]);
            $list_sppd = in_array($level, ["dosen", "pegawai"]) ? $this->queryBus->ask(new GetAllSPPDQuery($nidn, $nip, $tahun, TypeData::Default, false)) : collect([]);
            $list_absen = in_array($level, ["dosen", "pegawai"]) ? $this->queryBus->ask(new GetAllPresensiQuery($nidn, $nip, $tahun, null, TypeData::Default)) : collect([]);
            $list_klaim_absen = in_array($level, ["dosen", "pegawai"]) ? $this->queryBus->ask(new GetAllKlaimAbsenQuery($nidn, $nip, $tahun, TypeData::Default)) : collect([]);
            $master_kalendar = $this->queryBus->ask(new GetAllMasterKalendarQuery(1, 1, $tahun, TypeData::Default));
            // dd($list_cuti, $list_izin, $list_sppd, $master_kalendar);

            $key = match(true){
                !empty($nidn) => $nidn,
                !empty($nip) => $nip,
                default => "#",
            };
            // $list = Cache::remember("kalender-$key", 5*60, function () use(
            //     $format,
            //     $list_cuti,
            //     $list_izin,
            //     $list_sppd,
            //     $list_absen,
            //     $list_klaim_absen,
            //     $master_kalendar,
            // ){
                $list_libur_ = $master_kalendar->reduce(function ($carry, $item){
                    $start  = Carbon::parse($item->tanggal_mulai)->setTimezone('Asia/Jakarta');
                    $end    = Carbon::parse($item->tanggal_berakhir)->setTimezone('Asia/Jakarta');
                    $days   = $end->diffInDays($start);
                    for ($i = 0; $i <= $days; $i++) {
                        $carry[] = Carbon::parse($item->tanggal_mulai)->setTimezone('Asia/Jakarta')->addDays($i)->format('Y-m-d');
                    }
                    return $carry;
                }, []);
                $list_cuti_ = $list_cuti->reduce(function ($carry, $item){
                    $start  = Carbon::parse($item->tanggal_mulai)->setTimezone('Asia/Jakarta');
                    $end    = Carbon::parse($item->tanggal_akhir)->setTimezone('Asia/Jakarta');
                    $days   = $end->diffInDays($start);
                    for ($i = 0; $i <= $days; $i++) {
                        $carry[] = Carbon::parse($item->tanggal_mulai)->setTimezone('Asia/Jakarta')->addDays($i)->format('Y-m-d');
                    }
                    return $carry;
                }, []);
                $list_izin_ = $list_izin->reduce(function ($carry, $item){
                    $carry[] = $item->tanggal_pengajuan;
                    return $carry;
                }, []);
                $list_sppd_ = $list_sppd->reduce(function ($carry, $item){
                    $start  = Carbon::parse($item->tanggal_berangkat)->setTimezone('Asia/Jakarta');
                    $end    = Carbon::parse($item->tanggal_kembali)->setTimezone('Asia/Jakarta');
                    $days   = $end->diffInDays($start);
                    for ($i = 0; $i <= $days; $i++) {
                        $carry[] = Carbon::parse($item->tanggal_berangkat)->setTimezone('Asia/Jakarta')->addDays($i)->format('Y-m-d');
                    }
                    return $carry;
                }, []);

                $listKalendar = $master_kalendar->reduce(function ($carry, $item) use ($format) {
                    if ($format == "full-calendar") {
                        $start  = Carbon::parse($item->tanggal_mulai)->setTimezone('Asia/Jakarta');
                        $end    = Carbon::parse(($item->tanggal_berakhir == null || $item->tanggal_berakhir == $item->tanggal_mulai) ? $item->tanggal_mulai : $item->tanggal_berakhir)->setTimezone('Asia/Jakarta');
                        $days   = $end->diffInDays($start);
                        for ($i = 0; $i <= $days; $i++) {
                            $tgl = Carbon::parse($item->tanggal_mulai)->setTimezone('Asia/Jakarta')->addDays($i)->format('Y-m-d');
                            $carry[] = [
                                "title" => $item->keterangan ?? "tanpa keterangan",
                                "start" => $tgl,
                                "end" => $tgl,
                                "backgroundColor" => '#dc3545',
                                "borderColor" => "transparent",
                                // "className"=>"bg-danger"
                            ];
                        }
                    } else {
                        $start  = Carbon::parse($item->tanggal_mulai)->setTimezone('Asia/Jakarta');
                        $end    = Carbon::parse($item->tanggal_berakhir)->setTimezone('Asia/Jakarta');
                        $days   = $end->diffInDays($start);
                        for ($i = 0; $i <= $days; $i++) {
                            $carry[] = [
                                "id" => $item->id,
                                "tanggal" => Carbon::parse($item->tanggal_mulai)->setTimezone('Asia/Jakarta')->addDays($i)->format('Y-m-d'),
                                "keterangan" => $item->keterangan ?? "tanpa keterangan",
                            ];
                        }
                    }
                    return $carry;
                }, []);
    
                $listCuti = $list_cuti->reduce(function ($carry, $item) use ($format) {
                    if ($format == "full-calendar") {
                        $carry[] = [
                            "title" => $item->tujuan ?? "tanpa keterangan cuti",
                            "start" => $item->tanggal_mulai,
                            "end" => ($item->tanggal_akhir == null || $item->tanggal_akhir == $item->tanggal_mulai) ? $item->tanggal_mulai : $item->tanggal_akhir,
                            "backgroundColor" => '#ffc107',
                            "borderColor" => "transparent",
                            // "className"=>"bg-danger"
                        ];
                    } else {
                        $start  = Carbon::parse($item->tanggal_mulai)->setTimezone('Asia/Jakarta');
                        $end    = Carbon::parse($item->tanggal_akhir)->setTimezone('Asia/Jakarta');
                        $days   = $end->diffInDays($start);
                        for ($i = 0; $i <= $days; $i++) {
                            $carry[] = [
                                "id" => $item->id,
                                "tanggal" => Carbon::parse($item->tanggal_mulai)->setTimezone('Asia/Jakarta')->addDays($i)->format('Y-m-d'),
                                "keterangan" => $item->tujuan ?? "tanpa keterangan cuti",
                            ];
                        }
                    }
                    return $carry;
                }, []);
    
                $listIzin = $list_izin->reduce(function ($carry, $item) use ($format) {
                    if ($format == "full-calendar") {
                        $carry[] = [
                            "title" => $item->tujuan ?? "tanpa keterangan izin",
                            "start" => $item->tanggal_pengajuan,
                            "end" => $item->tanggal_pengajuan,
                            "backgroundColor" => '#0044cc',
                            "borderColor" => "transparent",
                            // "className"=>"bg-danger"
                        ];
                    } else {
                        $carry[] = [
                            "id" => $item->id,
                            "tanggal" => Carbon::parse($item->tanggal_pengajuan)->setTimezone('Asia/Jakarta')->format('Y-m-d'),
                            "keterangan" => $item->tujuan ?? "tanpa keterangan izin",
                        ];
                    }
                    return $carry;
                }, []);
    
                $listSPPD = $list_sppd->reduce(function ($carry, $item) use ($format) {
                    if ($format == "full-calendar") {
                        $carry[] = [
                            "title" => $item->keterangan ?? "tanpa keterangan sppd",
                            "start" => $item->tanggal_berangkat,
                            "end" => $item->tanggal_kembali,
                            "backgroundColor" => '#0dcaf0',
                            "borderColor" => "transparent",
                            // "className"=>"bg-danger"
                        ];
                    } else {
                        $start  = Carbon::parse($item->tanggal_berangkat)->setTimezone('Asia/Jakarta');
                        $end    = Carbon::parse($item->tanggal_kembali)->setTimezone('Asia/Jakarta');
                        $days   = $end->diffInDays($start);
                        for ($i = 0; $i <= $days; $i++) {
                            $carry[] = [
                                "id" => $item->id,
                                "tanggal" => Carbon::parse($item->tanggal_berangkat)->setTimezone('Asia/Jakarta')->format('Y-m-d'),
                                "keterangan" => $item->keterangan ?? "tanpa keterangan sppd",
                            ];
                        }
                    }
                    return $carry;
                }, []);
    
                $skip_tanggal = array_merge($list_libur_, $list_cuti_, $list_izin_, $list_sppd_);
                $listAbsen = $list_absen->reduce(function ($carry, $item) use ($format, $list_klaim_absen,$skip_tanggal) {
                    if(!in_array($item->tanggal,$skip_tanggal) && !Carbon::parse($item->tanggal)->isSunday()){
                        if ($format == "full-calendar") {
                            $klaim = $list_klaim_absen->where('status', 'terima')->where('tanggal', $item->tanggal);
                            $klaim = $klaim->count() == 1 ? $klaim[0] : null;
                            $tanggal = Carbon::parse($item->tanggal)->setTimezone('Asia/Jakarta');
                            $now = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');

                            $strategy = new DefaultStrategy();
                            if(is_null($klaim) && empty($item->absen_masuk) && $tanggal->lessThan($now)){
                                $strategy = new TidakAbsenStrategy();
                            } else if(!is_null($klaim) || (!empty($item->absen_masuk) && !Utility::isLate($item->absen_masuk, $item->tanggal))){
                                $strategy = new AbsenStrategy();
                            } else if(!is_null($klaim) || (!empty($item->absen_masuk) && !empty($item->absen_keluar) && Utility::is8Hour($item->tanggal, $item->absen_masuk, $item->absen_keluar))){
                                $strategy = new AbsenStrategy();
                            }

                            $context = new AbsenContext($strategy);
                            $background = $context->getBackground($klaim, $item, $tanggal, $now);
                            $title = $context->getTitle($klaim, $item, $tanggal, $now);

                            if (!empty($title)) {
                                $carry[] = [
                                    "title" => $title,
                                    "start" => $item->tanggal,
                                    "end" => $item->tanggal,
                                    "backgroundColor" => $background,
                                    "borderColor" => "transparent",
                                    // "className"=>"bg-danger"
                                ];
                            }
                        } else {
                            $carry[] = [
                                "id" => $item->id,
                                "tanggal" => date('Y-m-d', strtotime($item->tanggal_pengajuan)),
                                "keterangan" => $item->keterangan ?? "NA",
                            ];
                        }
                    }
    
                    return $carry;
                }, []);
                
                $list = array_merge($listAbsen, $listKalendar, $listCuti, $listIzin, $listSPPD);;
            // });

            return response()->json([
                "status" => "ok",
                "message" => "",
                "data" => $list,
            ]);
        } catch (Exception $e) {
            throw $e;
            return response()->json([
                "status" => "fail",
                "message" => "data tidak ditemukan",
                "data" => null,
                "log" => $e->getMessage()
            ]);
        }
    }
}
