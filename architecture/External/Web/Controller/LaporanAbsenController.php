<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\LaporanAbsen\List\GetAllLaporanAbsenQuery;
use Architecture\Domain\Entity\FolderX;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\External\Port\PdfX;
use Architecture\Shared\Creational\FileManager;
use Architecture\Shared\TypeData;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LaporanAbsenController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function Index(){
        ini_set('memory_limit', '-1');
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        $list_tanggal = [];
        for ($date = Carbon::now()->startOfMonth(); $date->lte($end); $date->addDay()) {
            $list_tanggal[] = $date->copy()->format('Y-m-d');
        }
        return view('laporan_absen.index',['list_tanggal'=>$list_tanggal,'start'=>$start->format('d F Y'),'end'=>$end->format('d F Y')]);
    }


    public function export(Request $request){
        try {
            $nidn           = $request->has('nidn')? $request->query('nidn'):null;
            $nip            = $request->has('nip')? $request->query('nip'):null;
            $tanggal_mulai  = $request->has('tanggal_mulai')? $request->query('tanggal_mulai'):null;
            $tanggal_akhir  = $request->has('tanggal_akhir')? $request->query('tanggal_akhir'):null;
            $type_export    = $request->has('type_export')? $request->query('type_export'):null;

            if(!is_null($nidn) && !is_null($nip)){
                throw new Exception("harus salah satu antara nidn dan nip");
            } else if(is_null($type_export)){
                throw new Exception("belum pilih cetak sebagai apa");
            }

            $file_name = "laporan_absen";
            if($nidn){
                $file_name = $file_name."_$nidn";
            }
            if($nip){
                $file_name = $file_name."_$nip";
            }
            if($tanggal_mulai && is_null($tanggal_akhir)){
                $file_name = $file_name."_$tanggal_mulai";
            }
            else if($tanggal_akhir && is_null($tanggal_mulai)){
                $file_name = $file_name."_$tanggal_akhir";
            } else if($tanggal_mulai && $tanggal_akhir){
                $file_name = $file_name."_$tanggal_mulai-$tanggal_akhir";
            }
            $laporan = $this->queryBus->ask(new GetAllLaporanAbsenQuery($nidn,$nip,$tanggal_mulai,$tanggal_akhir,TypeData::Default));

            return $this->generateHtml(true, 0, null, null, $laporan);
            if($type_export=="pdf"){
                $file = PdfX::From(
                    "template.export_absen", 
                    $laporan, 
                    FolderX::FromPath(public_path('export/pdf')), 
                    "$file_name.pdf",
                    true
                );
            } else{
                throw new Exception("export type '$type_export' not implementation");
            }
    
            return FileManager::StreamFile($file);

        } catch (Exception $e) {
            throw $e;
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('izin.index');
        }
    }

    public function generateHtml($initial=true,$index=0,$i_t=null,$i_data=null,$source)
    {
        $html = "";
        if($initial){
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
        } else if(!$initial && !is_null($i_data) && !is_null($i_t)){
            $data = array_key_exists($i_data, $source['list_data'])? $source['list_data'][$i_t]:null;
            if(is_null($data)){
                if($i_t<count($source['list_tanggal'])){
                    $html .= $this->generateHtml(false, $index, $i_t+1, $i_data, $source);
                }
                if($i_data<count($source['list_data'])){
                    $html .= $this->generateHtml(false, $index, $i_t, $i_data+1, $source);
                }
                return $html;
            }
            $nama = $data['type'] == "pegawai" ? $data['pengguna']['nama'] : $data['pengguna']['nama_dosen'];
            $kode = $data['type'] == "pegawai" ? $data['pengguna']['nip'] : $data['pengguna']['NIDN'];

            $html .= '<tr>';
            $html .= '  <td>' . ($index + 1) . '</td>';
            $html .= '  <td>' . $nama . ' - ' . $kode . '</td>';

            $tanggal = array_key_exists($i_t, $source['list_tanggal'])? $source['list_tanggal'][$i_t]:null;

            $html .= '  <td>';
            $html .= '      <table>';
            $html .= '          <tr>';
            $html .= '              <td class="column_min">';

            $aturan_jam = "08:00 - 15:00";
            $dayOfWeek = isset($tanggal)? Carbon::parse($tanggal)->setTimezone('Asia/Jakarta')->dayOfWeek:null;
            if ($dayOfWeek == Carbon::FRIDAY) {
                $aturan_jam = "08:00 - 14:00";
            } elseif ($dayOfWeek == Carbon::SATURDAY) {
                $aturan_jam = "08:00 - 12:00";
            }

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

            if($i_t<count($source['list_tanggal'])){
                $html .= $this->generateHtml(false, $index, $i_t+1, $i_data, $source);
            }
            if($i_data<count($source['list_data'])){
                $html .= $this->generateHtml(false, $index, $i_t, $i_data+1, $source);
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
