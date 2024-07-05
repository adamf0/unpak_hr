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
    ) {
    }

    public function isLate($tanggal_jam_masuk = null, $tanggal = null)
    {
        $masuk = new Date($tanggal_jam_masuk);
        $keluar = new Date($tanggal . " 08:01:00");
        return $masuk->isGreater($keluar);
    }
    public function is8Hour($tanggal_jam_masuk = null, $tanggal_jam_keluar = null)
    {
        $masuk = new Date($tanggal_jam_masuk);
        $keluar = new Date($tanggal_jam_keluar);

        if (!empty($keluar) && !$this->isLate($masuk, $keluar)) {
            $jam_pulang = "14:59:00";
            if (Carbon::now()->setTimezone('Asia/Jakarta')->dayOfWeek == Carbon::FRIDAY) {
                $jam_pulang = "13:59:00";
            } elseif (Carbon::now()->setTimezone('Asia/Jakarta')->dayOfWeek == Carbon::SATURDAY) {
                $jam_pulang = "11:59:00";
            }
            $keluar = new Date($tanggal_jam_keluar . " $jam_pulang");
            return $keluar->isGreater($keluar);
        } else if (!empty($keluar) && $this->isLate($masuk, $keluar)) {
            $keluar = new Date(Carbon::parse($tanggal_jam_keluar)->setTimezone('Asia/Jakarta')->addHour(8)->toISOString());
            return $keluar->isGreater($keluar);
        } else
            return false;
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
            $list_absen = in_array($level, ["dosen", "pegawai"]) ? $this->queryBus->ask(new GetAllPresensiQuery($nidn, $nip, $tahun, TypeData::Default)) : collect([]);
            $list_klaim_absen = in_array($level, ["dosen", "pegawai"]) ? $this->queryBus->ask(new GetAllKlaimAbsenQuery($nidn, $nip, $tahun, TypeData::Default)) : collect([]);
            $master_kalendar = $this->queryBus->ask(new GetAllMasterKalendarQuery(1, 1, $tahun, TypeData::Default));
            // dd($list_cuti, $list_izin, $list_sppd, $master_kalendar);

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
                $carry[] = $item->tanggal_pengajuan;
                return $carry;
            }, []);
            $skip_tanggal = array_merge($list_libur_, $list_cuti_, $list_izin_, $list_sppd_);
            dd($list_libur_, $list_cuti_, $list_izin_, $list_sppd, $list_absen);
            
            $listKalendar = $master_kalendar->reduce(function ($carry, $item) use ($format) {
                if ($format == "full-calendar") {
                    $carry[] = [
                        "title" => $item->keterangan ?? "tanpa keterangan",
                        "start" => $item->tanggal_mulai,
                        "end" => ($item->tanggal_berakhir == null || $item->tanggal_berakhir == $item->tanggal_mulai) ? $item->tanggal_mulai : $item->tanggal_berakhir,
                        "backgroundColor" => '#dc3545',
                        "borderColor" => "transparent",
                        // "className"=>"bg-danger"
                    ];
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
                    $carry[] = [
                        "id" => $item->id,
                        "tanggal" => Carbon::parse($item->tanggal_pengajuan)->setTimezone('Asia/Jakarta')->format('Y-m-d'),
                        "keterangan" => $item->keterangan ?? "tanpa keterangan sppd",
                    ];
                }
                return $carry;
            }, []);

            $listAbsen = $list_absen->reduce(function ($carry, $item) use ($format, $list_klaim_absen,$skip_tanggal) {
                if(!in_array($item->tanggal,$skip_tanggal)){
                    if ($format == "full-calendar") {
                        $klaim = $list_klaim_absen->where('status', 'terima')->where('tanggal', $item->tanggal);
                        $klaim = $klaim->count() == 1 ? $klaim[0] : null;
    
                        $background = match (true) {
                            is_null($klaim) && empty($item->absen_masuk) && Carbon::parse($item->tanggal)->setTimezone('Asia/Jakarta')->lessThan(Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d')) => "#dc3545", //tidak masuk
                            !is_null($klaim) || (!empty($item->absen_masuk) && !$this->isLate($item->absen_masuk, $item->tanggal)) => "#198754", //masuk
                            default => "#000"
                        };
    
                        $title = match (true) {
                            is_null($klaim) && empty($item->absen_masuk) && Carbon::parse($item->tanggal)->setTimezone('Asia/Jakarta')->lessThan(Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d')) => "tidak masuk", //tidak masuk
                            !is_null($klaim) => sprintf(
                                "%s - %s %s",
                                (date('H:i:s', strtotime(empty($item->absen_masuk) ? $klaim->jam_masuk : $item->absen_masuk))),
                                (date('H:i:s', strtotime(empty($item->absen_keluar) ? $klaim->jam_keluar : $item->absen_keluar))),
                                ((!empty($klaim->jam_masuk) || !empty($klaim->jam_keluar)) ? "(klaim)" : "")
                            ), //klaim
    
                            !empty($item->absen_masuk) && !$this->isLate($item->absen_masuk, $item->tanggal) => sprintf("%s - %s", date('H:i:s', strtotime($item->absen_masuk)), (empty($item->absen_keluar) ? "" : date('H:i:s', strtotime($item->absen_keluar)))), //masuk
                            default => sprintf(
                                "%s - %s",
                                empty($item->absen_masuk) ? "-" : date('H:i:s', strtotime($item->absen_masuk)),
                                empty($item->absen_keluar) ? "-" : date('H:i:s', strtotime($item->absen_keluar))
                            )
                        };
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
            // dd($list_libur_, $list_cuti_, $list_izin_, $list_sppd_, $listAbsen);

            $list = array_merge($listKalendar, $listCuti, $listIzin, $listSPPD, $listAbsen);

            return response()->json([
                "status" => "ok",
                "message" => "",
                "data" => $list,
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => "fail",
                "message" => "data tidak ditemukan",
                "data" => null,
                "log" => $e->getMessage()
            ]);
        }
    }
}
