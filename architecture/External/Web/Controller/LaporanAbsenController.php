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
}
