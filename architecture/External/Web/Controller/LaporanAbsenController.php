<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Domain\Entity\FolderX;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\External\Persistance\ORM\Izin;
use Architecture\External\Port\PdfX;
use Architecture\Shared\Creational\FileManager;
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
        $list_data = DB::table('laporan_merge_absen_izin_cuti')->whereBetween('tanggal',[$start->format('Y-m-d'),$end->format('Y-m-d')])->orderBy('tanggal')->get();
        
        $groupedData = collect($list_data)->groupBy(function ($item) {
            return $item->tanggal . $item->nidn . $item->nip;
        });
        $list_data = $groupedData->map(function ($group) {
            return $group->reduce(function ($carry, $item) {
                $info = json_decode($item->info, true);
                $carry['info'][] = $info;
                return $carry;
            }, [
                'nidn' => $group->first()->nidn,
                'nip' => $group->first()->nip,
                'tanggal' => $group->first()->tanggal,
                'info' => []
            ]);
        })->values();

        return view('laporan_absen.index',['list_data'=>$list_data,'list_tanggal'=>$list_tanggal,'start'=>$start->format('d F Y'),'end'=>$end->format('d F Y')]);
    }


    public function export(Request $request){
        try {
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time','-1');
            
            $nidn           = $request->has('nidn')? $request->query('nidn'):null;
            $nip            = $request->has('nip')? $request->query('nip'):null;
            $tanggal_mulai  = $request->has('tanggal_mulai')? $request->query('tanggal_mulai'):Carbon::now()->startOfMonth();
            $tanggal_akhir  = $request->has('tanggal_akhir')? $request->query('tanggal_akhir'):Carbon::parse($tanggal_mulai)->endOfMonth()->format('Y-m-d');
            $type_export    = $request->has('type_export')? $request->query('type_export'):null;

            if(!is_null($nidn) && !is_null($nip)){
                throw new Exception("harus salah satu antara nidn dan nip");
            } else if(is_null($type_export)){
                throw new Exception("belum pilih cetak sebagai apa");
            }

            $file_name = "laporan_absen";
            $laporan = DB::table('laporan_merge_absen_izin_cuti');
            if($nidn){
                $laporan->where('nidn',$nidn);
                $file_name = $file_name."_$nidn";
            }
            if($nip){
                $laporan->where('nip',$nip);
                $file_name = $file_name."_$nip";
            }
            if($tanggal_mulai && is_null($tanggal_akhir)){
                $laporan->where('tanggal',$tanggal_mulai);
                $file_name = $file_name."_$tanggal_mulai";
            }
            else if($tanggal_akhir && is_null($tanggal_mulai)){
                $laporan->where('tanggal',$tanggal_akhir);
                $file_name = $file_name."_$tanggal_akhir";
            } else if($tanggal_mulai && $tanggal_akhir){
                $laporan->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir]);

                $file_name = $file_name."_$tanggal_mulai-$tanggal_akhir";
            }
            $list_data = $laporan->get();
            
            $start = is_null($tanggal_mulai)? Carbon::parse($tanggal_mulai)->startOfMonth():Carbon::parse($tanggal_mulai);
            $end = is_null($tanggal_akhir)? Carbon::parse($tanggal_mulai)->endOfMonth():Carbon::parse($tanggal_akhir);
            $list_tanggal = [];
            for ($date = (is_null($tanggal_mulai)? Carbon::parse($tanggal_mulai)->startOfMonth():Carbon::parse($tanggal_mulai)); $date->lte($end); $date->addDay()) {
                $list_tanggal[] = $date->copy()->format('Y-m-d');
            }            
            $groupedData = collect($list_data)->groupBy(function ($item) {
                return $item->tanggal . $item->nidn . $item->nip;
            });
            $list_data = $groupedData->map(function ($group) {
                return $group->reduce(function ($carry, $item) {
                    $info = json_decode($item->info, true);
                    $carry['info'][] = $info;
                    return $carry;
                }, [
                    'nidn' => $group->first()->nidn,
                    'nip' => $group->first()->nip,
                    'tanggal' => $group->first()->tanggal,
                    'info' => []
                ]);
            })->values();

            if($type_export=="pdf"){
                $file = PdfX::From(
                    "template.export_absen", 
                    [
                        'list_data'=>$list_data,
                        'list_tanggal'=>$list_tanggal,
                        'start'=>$start->format('d F Y'),
                        'end'=>$end->format('d F Y')
                    ], 
                    FolderX::FromPath(public_path('export/pdf')), 
                    "$file_name.pdf",
                    true
                );
            } else{
                throw new Exception("export type '$type_export' not implementation");
            }
    
            return FileManager::StreamFile($file);

        } catch (Exception $e) {
            // throw $e;
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('izin.index');
        }
    }
}
