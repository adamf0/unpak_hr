<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use App\Models\Cuti;
use App\Models\EPribadi;
use App\Models\Izin;
use App\Models\Klaim;
use App\Models\NPribadi;
use App\Models\SppdAnggota;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\LaporanAbsen\List\GetAllLaporanAbsenQuery;
use Architecture\Domain\Entity\FolderX;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\External\Port\ExportAbsenXls;
use Architecture\External\Port\PdfX;
use Architecture\Shared\Creational\FileManager;
use Architecture\Shared\TypeData;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class LaporanAbsenController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}

    public function Index(Request $request)
    {
        $dosen = EPribadi::with(['pengangkatan', 'payroll'])
            ->selectRaw('TRIM(nip) as nip, TRIM(nidn) as nik, REPLACE(TRIM(nama), CHAR(9), "") as nama, "DOSEN" as status')
            ->whereNotIn('nidn', ['', '-', '0', 'KONTRAK'])
            ->whereHas('payroll', function ($query) {
                $query->whereIn('tgl_keluar', ['', null]);
            })
            ->get();
        $tendik = NPribadi::with(['pengangkatan', 'payroll'])
            ->selectRaw('TRIM(nip) as nip, TRIM(nip) as nik, REPLACE(TRIM(nama), CHAR(9), "") as nama, "TENDIK" as status')
            ->whereNotIn('nip', ['56'])
            ->whereHas('payroll', function ($query) {
                $query->whereIn('tgl_keluar', ['', null]);
            })
            ->get();
        $pegawai = collect($dosen)->merge($tendik);

        $start = $request->tanggal_mulai ?? '';
        $end = $request->tanggal_akhir ?? '';
        $presensi = Absen::selectRaw('IF(nip = "", nidn, nip) as nik, tanggal, absen_masuk, absen_keluar')
            ->when($start && $end, function ($q) use ($start, $end) {
                $q->where('tanggal', '>=', $start)
                    ->where('tanggal', '<=', $end);
            })->whereNot('absen_masuk', null)->get();

        $klaim = Klaim::with('absen:id,tanggal')
            ->selectRaw('IF(nip = "", nidn, nip) as nik, id_presensi, jam_masuk, jam_keluar')
            ->whereHas('absen', function ($query) use ($start, $end) {
                $query->where('tanggal', '>=', $start)
                    ->where('tanggal', '<=', $end);
            })
            ->where('status', 'terima')->get();

        $cuti = Cuti::selectRaw('IF(nip = "", nidn, nip) as nik, tanggal_mulai, tanggal_akhir, status')
            ->when($start && $end, function ($q) use ($start, $end) {
                $q->where(function ($q) use ($start) {
                    $q->where('tanggal_mulai', '>=', $start)
                        ->where('tanggal_akhir', '<=', $start);
                })->orWhere(function ($q) use ($end) {
                    $q->where('tanggal_mulai', '<=', $end)
                        ->where('tanggal_akhir', '>=', $end);
                })->orWhere(function ($q) use ($start, $end) {
                    $q->where('tanggal_mulai', '>=', $start)
                        ->where('tanggal_akhir', '<=', $end);
                });
            })
            ->where('status', 'terima sdm')->get();
        $izin = Izin::selectRaw('IF(nip = "", nidn, nip) as nik, tanggal_pengajuan, status')
            ->when($start && $end, function ($q) use ($start, $end) {
                $q->where('tanggal_pengajuan', '>=', $start)
                    ->where('tanggal_pengajuan', '<=', $end);
            })
            ->where('status', 'terima sdm')->get();

        $sppd = SppdAnggota::selectRaw('IF(nip = "", nidn, nip) as nik, id_sppd')->whereHas('sppd', function ($query) use ($start, $end) {
            $query->where('status', 'terima sdm')
                ->when($start && $end, function ($q) use ($start, $end) {
                    $q->where(function ($q) use ($start) {
                        $q->where('tanggal_berangkat', '>=', $start)
                            ->where('tanggal_kembali', '<=', $start);
                    })->orWhere(function ($q) use ($end) {
                        $q->where('tanggal_berangkat', '<=', $end)
                            ->where('tanggal_kembali', '>=', $end);
                    })->orWhere(function ($q) use ($start, $end) {
                        $q->where('tanggal_berangkat', '>=', $start)
                            ->where('tanggal_kembali', '<=', $end);
                    });
                });
        })->with('sppd:id,tanggal_berangkat,tanggal_kembali,status')->get();

        return view('laporan_absen.index', [
            'listpegawai' => $pegawai,
            'listpresensi' => $presensi,
            'listklaim' => $klaim,
            'listcuti' => $cuti,
            'listizin' => $izin,
            'listsppd' => $sppd,
            'start' => $start,
            'end' => $end,
        ]);
    }


    public function export(Request $request)
    { //gagal harus lebih spesifik
        ini_set('memory_limit', '8192M');

        try {
            $nidn           = $request->has('nidn') ? $request->query('nidn') : null;
            $nip            = $request->has('nip') ? $request->query('nip') : null;
            $tanggal_mulai  = $request->has('tanggal_mulai') ? $request->query('tanggal_mulai') : null;
            $tanggal_akhir  = $request->has('tanggal_akhir') ? $request->query('tanggal_akhir') : null;
            $type           = $request->has('type') ? $request->query('type') : null;
            $type_export    = $request->has('type_export') ? $request->query('type_export') : null;

            if (!is_null($nidn) && !is_null($nip)) {
                throw new Exception("harus salah satu antara nidn dan nip");
            } else if (is_null($type_export)) {
                throw new Exception("belum pilih cetak sebagai apa");
            }

            $file_name = "laporan_absen";
            if ($nidn) {
                $file_name = $file_name . "_$nidn";
            }
            if ($nip) {
                $file_name = $file_name . "_$nip";
            }
            if ($tanggal_mulai && is_null($tanggal_akhir)) {
                $file_name = $file_name . "_$tanggal_mulai";
            } else if ($tanggal_akhir && is_null($tanggal_mulai)) {
                $file_name = $file_name . "_$tanggal_akhir";
            } else if ($tanggal_mulai && $tanggal_akhir) {
                $file_name = $file_name . "_$tanggal_mulai-$tanggal_akhir";
            }
            $laporan = Cache::remember($file_name, 1 * 60, function () use ($nidn, $nip, $tanggal_mulai, $tanggal_akhir, $type) {
                return $this->queryBus->ask(new GetAllLaporanAbsenQuery($nidn, $nip, $tanggal_mulai, $tanggal_akhir, $type, TypeData::Default));
            });
            // $laporan = $this->queryBus->ask(new GetAllLaporanAbsenQuery($nidn,$nip,$tanggal_mulai,$tanggal_akhir,$type,TypeData::Default));

            if ($type_export == "pdf") {
                throw new Exception("export pdf masih di perbaiki");
                return $this->generateHtml(true, 0, null, null, $laporan);
                $file = PdfX::From(
                    "template.export_absen",
                    $laporan,
                    FolderX::FromPath(public_path('export/pdf')),
                    "$file_name.pdf",
                    true
                );
                return FileManager::StreamFile($file);
            } else {
                $listData = collect($laporan['list_data'])->map(function ($item) {
                    dump($item['type']);
                    $nama =  "NA";
                    if ($item['type'] == "dosen") {
                        $nama = $item['pengguna']['nama_dosen'];
                    } else if ($item['type'] == "pegawai") {
                        if (!array_key_exists("nama", $item['pengguna'])) {
                            dd($item);
                        }
                        $nama = $item['pengguna']['nama'];
                    }
                    // $nama = match($item['type']){
                    //     "dosen"=>$item['pengguna']['nama_dosen'],
                    //     "pegawai"=>$item['pengguna']['nama'],
                    // };
                    unset($item['pengguna']);
                    unset($item['type']);
                    $item = array_merge(['nama' => $nama], (array) $item);

                    $keys = array_keys((array)$item);
                    foreach ($keys as $key) {
                        if ($key !== 'nama') {
                            $listInfo = $item[$key];
                            $info = array_reduce($listInfo, function ($carry, $info) {
                                $type = strtolower($info->info->type);
                                if ($type == "absen") {
                                    $masuk = $info->info->keterangan['masuk'];
                                    $keluar = $info->info->keterangan['keluar'];

                                    $carry[] = match (true) {
                                        !empty($masuk) && !empty($keluar) => date("H:i:s", strtotime($masuk)) . " - " . date("H:i:s", strtotime($keluar)),
                                        !empty($masuk) && empty($keluar) => date("H:i:s", strtotime($masuk)) . " - tidak ada absen keluar",
                                        empty($masuk) && !empty($keluar) => "tidak ada absen masuk - " . date("H:i:s", strtotime($keluar)),
                                        default => "tidak masuk"
                                    };
                                } elseif ($type == "izin" || $type == "cuti" || $type == "sppd") {
                                    $carry[] = $type; //$info->info->keterangan['tujuan'];
                                }
                                return $carry;
                            }, []);
                            $item[$key] = match (true) {
                                count($info) == 1 => $info[0],
                                count($info) > 1 => end($info),
                                default => ""
                            };
                        }
                    }
                    return $item;
                });
                $listTanggalFormat = collect($laporan['list_tanggal'])->reduce(function ($carry, $item) {
                    $carry[] = date('d F Y', strtotime($item));
                    return $carry;
                }, []);

                return Excel::download(new ExportAbsenXls(collect($listData), array_merge(["nama"], $listTanggalFormat)), "$file_name.xlsx");
            }
        } catch (Exception $e) {
            throw $e;
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('laporan_absen.index');
        }
    }

    public function generateHtml($initial = true, $index = 0, $i_t = null, $i_data = null, $source)
    {
        $html = "";
        if ($initial) {
            $html .= '<div class="card-body row">';
            $html .= '<div class="col-12">';
            $html .= '<h5>Per Tanggal ' . $source['start'] . ' - ' . $source['end'] . '</h5>';
            $html .= '</div>';
            $html .= '<div class="col-12 table-responsive">';
            $html .= '<table id="tb" class="table table-striped text-center">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th>#</th>';
            $html .= '<th>Nama</th>';

            foreach ($source['list_tanggal'] as $tanggal) {
                $html .= '<th>' . Carbon::parse($tanggal)->setTimezone('Asia/Jakarta')->format('d') . '</th>';
            }

            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            $html .= $this->generateHtml(false, 0, 0, 0, $source);
        } else if (!$initial && !is_null($i_data) && !is_null($i_t) && $i_t < count($source['list_data'])) {
            // dump($i_t, $i_data, $source['list_data']);
            $data = array_key_exists($i_data, $source['list_data']) ? $source['list_data'][$i_t] : null;
            if (is_null($data)) {
                if ($i_t < count($source['list_tanggal'])) {
                    $html .= $this->generateHtml(false, $index, $i_t + 1, $i_data, $source);
                }
                if ($i_data < count($source['list_data'])) {
                    $html .= $this->generateHtml(false, $index, $i_t, $i_data + 1, $source);
                }
                return $html;
            }
            $nama = $data['type'] == "pegawai" ? $data['pengguna']['nama'] : $data['pengguna']['nama_dosen'];
            $kode = $data['type'] == "pegawai" ? $data['pengguna']['nip'] : $data['pengguna']['NIDN'];

            $html .= '<tr>';
            $html .= '  <td>' . ($index + 1) . '</td>';
            $html .= '  <td>' . $nama . ' - ' . $kode . '</td>';

            $tanggal = array_key_exists($i_t, $source['list_tanggal']) ? $source['list_tanggal'][$i_t] : null;

            $html .= '  <td>';
            $html .= '      <table>';
            $html .= '          <tr>';
            $html .= '              <td class="column_min">';

            $aturan_jam = "08:00 - 15:00";
            $dayOfWeek = isset($tanggal) ? Carbon::parse($tanggal)->setTimezone('Asia/Jakarta')->dayOfWeek : null;
            if ($dayOfWeek == Carbon::FRIDAY) {
                $aturan_jam = "08:00 - 14:00";
            }
            // elseif ($dayOfWeek == Carbon::SATURDAY) {
            //     $aturan_jam = "08:00 - 12:00";
            // }

            $html .= $aturan_jam;
            $html .= '              </td>';
            $html .= '          </tr>';

            $keterangan = "";
            if (isset($data[$tanggal])) {
                foreach ($data[$tanggal] as $detail) {
                    $info = $detail->info;
                    switch ($info->type) {
                        case 'absen':
                            if (empty($info->keterangan['masuk']) && empty($info->keterangan['keluar'])) {
                                $keterangan = "<span class='badge bg-danger'>Tidak Masuk</span>";
                            } elseif (!empty($info->keterangan['masuk']) && empty($info->keterangan['keluar'])) {
                                $masuk = Carbon::parse($info->keterangan['masuk'])->setTimezone('Asia/Jakarta')->format('H:i');
                                $keterangan = "<span class='badge bg-success'>" . $masuk . "</span> - <span class='badge bg-danger'>Masih Masuk</span>";
                            } else {
                                $masuk = Carbon::parse($info->keterangan['masuk'])->setTimezone('Asia/Jakarta')->format('H:i');
                                $keluar = Carbon::parse($info->keterangan['keluar'])->setTimezone('Asia/Jakarta')->format('H:i');
                                $keterangan = "<span class='badge bg-success'>" . $masuk . "</span> - <span class='badge bg-danger'>" . $keluar . "</span>";
                            }
                            break;
                        case 'izin':
                            $keterangan = "<span class='badge bg-primary'>Izin</span>";
                            break;
                        case 'cuti':
                            $keterangan = "<span class='badge bg-warning text-black'>Cuti</span>";
                            break;
                    }
                }
            }
            $html .= '          <tr>';
            $html .= '              <td>' . $keterangan . '</td>';
            $html .= '          </tr>';
            $html .= '      </table>';
            $html .= '  </td>';
            $html .= '</tr>';

            if ($i_t < count($source['list_tanggal'])) {
                $html .= $this->generateHtml(false, $index, $i_t + 1, $i_data, $source);
            }
            if ($i_data < count($source['list_data'])) {
                $html .= $this->generateHtml(false, $index, $i_t, $i_data + 1, $source);
            }
        }

        // foreach ($source['list_data'] as $index => $data) {
        //     // $nama = $data['type'] == "pegawai" ? $data['pengguna']['nama'] : $data['pengguna']['nama_dosen'];
        //     // $kode = $data['type'] == "pegawai" ? $data['pengguna']['nip'] : $data['pengguna']['NIDN'];

        //     // $html .= '<tr>';
        //     // $html .= '<td>' . ($index + 1) . '</td>';
        //     // $html .= '<td>' . $nama . ' - ' . $kode . '</td>';

        //     foreach ($source['list_tanggal'] as $tanggal) {
        //         // $html .= '<td>';
        //         // $html .= '<table>';
        //         // $html .= '<tr>';
        //         // $html .= '<td class="column_min">';

        //         // $aturan_jam = "08:00 - 15:00";
        //         // $dayOfWeek = Carbon::parse($tanggal)->setTimezone('Asia/Jakarta')->dayOfWeek;
        //         // if ($dayOfWeek == Carbon::FRIDAY) {
        //         //     $aturan_jam = "08:00 - 14:00";
        //         // } elseif ($dayOfWeek == Carbon::SATURDAY) {
        //         //     $aturan_jam = "08:00 - 12:00";
        //         // }

        //         // $html .= $aturan_jam;
        //         // $html .= '</td>';
        //         // $html .= '</tr>';

        //         // $keterangan = "";
        //         if (isset($data[$tanggal])) {
        //             // foreach ($data[$tanggal] as $detail) {
        //             //     $info = $detail['info'];
        //             //     switch ($info->type) {
        //             //         case 'absen':
        //             //             if (empty($info->keterangan['masuk']) && empty($info->keterangan['keluar'])) {
        //             //                 $keterangan = "<span class='badge bg-danger'>Tidak Masuk</span>";
        //             //             } elseif (!empty($info->keterangan['masuk']) && empty($info->keterangan['keluar'])) {
        //             //                 $masuk = Carbon::parse($info->keterangan['masuk'])->setTimezone('Asia/Jakarta')->format('H:i');
        //             //                 $keterangan = "<span class='badge bg-success'>" . $masuk . "</span> - <span class='badge bg-danger'>Masih Masuk</span>";
        //             //             } else {
        //             //                 $masuk = Carbon::parse($info->keterangan['masuk'])->setTimezone('Asia/Jakarta')->format('H:i');
        //             //                 $keluar = Carbon::parse($info->keterangan['keluar'])->setTimezone('Asia/Jakarta')->format('H:i');
        //             //                 $keterangan = "<span class='badge bg-success'>" . $masuk . "</span> - <span class='badge bg-danger'>" . $keluar . "</span>";
        //             //             }
        //             //             break;
        //             //         case 'izin':
        //             //             $keterangan = "<span class='badge bg-primary'>Izin</span>";
        //             //             break;
        //             //         case 'cuti':
        //             //             $keterangan = "<span class='badge bg-warning text-black'>Cuti</span>";
        //             //             break;
        //             //     }
        //             // }
        //         }

        //         // $html .= '<tr>';
        //         // $html .= '<td>' . $keterangan . '</td>';
        //         // $html .= '</tr>';
        //         // $html .= '</table>';
        //         // $html .= '</td>';
        //     }

        //     // $html .= '</tr>';
        // }

        // $html .= '</tbody>';
        // $html .= '</table>';
        // $html .= '</div>';
        // $html .= '</div>';

        return $html;
    }
}
