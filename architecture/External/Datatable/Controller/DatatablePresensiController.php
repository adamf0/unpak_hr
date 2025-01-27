<?php

namespace Architecture\External\Datatable\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Presensi\List\GetAllPresensiQuery;
use Architecture\Domain\Enum\FormatDate;
use Architecture\Domain\ValueObject\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\DataTables as DataTables;

class DatatablePresensiController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function index(Request $request){
        $nidn = $request->has('nidn')? $request->query('nidn'):null;
        $nip = $request->has('nip')? $request->query('nip'):null;
        // $tahun = $request->has('tahun')? $request->query('tahun'):date('Y-m-d');
        $filter = $request->has('filter')? $request->query('filter'):null;
        
        $datas = $this->queryBus->ask(new GetAllPresensiQuery($nidn,$nip,date('Y'),date('Y-m-d')));
        if($filter=="dosen"){
            $datas = $datas->filter(fn($item)=>!is_null($item->GetDosen()));
        } else if($filter=="pegawai"){
            $datas = $datas->filter(fn($item)=>!is_null($item->GetPegawai()));
        }

        
        $datas = Cache::remember("list-presensi-$filter", 5*60, function () use($datas,$filter){
                    return $datas
                        // ->filter(function($item){
                        //     return $item->getTanggal()->isEqual(new Date(date('Y-m-d')));
                        // })
                        ->reduce(function ($carry, $item) use($filter){
                            if($filter=="dosen"){
                                $arr = [$item->GetDosen()?->GetNama(),$item->GetDosen()?->GetNIDN()];
                                if(!empty($item->GetDosen()?->GetUnitKerja()))
                                $arr[] = $item->GetDosen()?->GetUnitKerja();
                                
                                $carry[] = [
                                    "nama"=>implode(" - ",$arr),
                                    "tanggal"=>$item->GetTanggal()?->toFormat(FormatDate::LDFY),
                                    "masuk"=>$item->GetAbsenMasuk()?->toFormat(FormatDate::HIS),
                                    "keluar"=>$item->GetAbsenKeluar()?->toFormat(FormatDate::HIS),
                                    "catatan_telat"=>$item->GetCatatanTelat(),
                                    "catatan_pulang"=>$item->GetCatatanPulang(),
                                    "id"=>$item->GetId(),
                                ];
                            } else if($filter=="pegawai"){
                                $arr = [$item->GetPegawai()?->GetNama(),$item->GetPegawai()?->GetNIP()];
                                if(!empty($item->GetPegawai()?->GetUnit()))
                                $arr[] = $item->GetPegawai()?->GetUnit();

                                $carry[] = [
                                    "nama"=>implode(" - ",$arr),
                                    "tanggal"=>$item->GetTanggal()?->toFormat(FormatDate::LDFY),
                                    "masuk"=>$item->GetAbsenMasuk()?->toFormat(FormatDate::HIS),
                                    "keluar"=>$item->GetAbsenKeluar()?->toFormat(FormatDate::HIS),
                                    "catatan_telat"=>$item->GetCatatanTelat(),
                                    "catatan_pulang"=>$item->GetCatatanPulang(),
                                    "id"=>$item->GetId(),
                                ];
                            } else{
                                return [
                                    "tanggal"=>$item->GetTanggal()?->toFormat(FormatDate::LDFY),
                                    "masuk"=>$item->GetAbsenMasuk()?->toFormat(FormatDate::HIS),
                                    "keluar"=>$item->GetAbsenKeluar()?->toFormat(FormatDate::HIS),
                                    "catatan_telat"=>$item->GetCatatanTelat(),
                                    "catatan_pulang"=>$item->GetCatatanPulang(),
                                    "id"=>$item->GetId(),
                                ];
                            }

                            return $carry;
                        });
        });

        $table = DataTables::of($datas)
        ->addIndexColumn()
        ->make(true);

        return $table;
    }
}
