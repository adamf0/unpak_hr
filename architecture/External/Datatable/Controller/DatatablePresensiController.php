<?php

namespace Architecture\External\Datatable\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Presensi\List\GetAllPresensiQuery;
use Architecture\Domain\Enum\FormatDate;
use Architecture\Domain\ValueObject\Date;
use Illuminate\Http\Request;
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
        $tahun = $request->has('tahun')? $request->query('tahun'):null;
        $filter = $request->has('filter')? $request->query('filter'):null;
        
        $datas = $this->queryBus->ask(new GetAllPresensiQuery($nidn,$nip,$tahun));
        if($filter=="dosen"){
            $datas = $datas->filter(fn($item)=>!is_null($item->GetDosen()));
        } else if($filter=="pegawai"){
            $datas = $datas->filter(fn($item)=>!is_null($item->GetPegawai()));
        }

        $datas = $datas->filter(function($item){
                        return $item->getTanggal()->isEqual(new Date(date('Y-m-d')));
                    })
                    ->map(function ($item) use($filter){
                        return match(true){
                            $filter=="dosen"=>[
                                "nama"=>$item->GetDosen()?->GetNama()." - ".$item->GetDosen()?->GetNIDN(),
                                "tanggal"=>$item->GetTanggal()?->toFormat(FormatDate::LDFY),
                                "masuk"=>$item->GetAbsenMasuk()?->toFormat(FormatDate::HIS),
                                "keluar"=>$item->GetAbsenKeluar()?->toFormat(FormatDate::HIS),
                                "catatan_telat"=>$item->GetCatatanTelat(),
                                "catatan_pulang"=>$item->GetCatatanPulang(),
                            ],
                            $filter=="pegawai"=>[
                                "nama"=>$item->GetPegawai()?->GetNama()." - ".$item->GetPegawai()?->GetNIP(),
                                "tanggal"=>$item->GetTanggal()?->toFormat(FormatDate::LDFY),
                                "masuk"=>$item->GetAbsenMasuk()?->toFormat(FormatDate::HIS),
                                "keluar"=>$item->GetAbsenKeluar()?->toFormat(FormatDate::HIS),
                                "catatan_telat"=>$item->GetCatatanTelat(),
                                "catatan_pulang"=>$item->GetCatatanPulang(),
                            ],
                            default=>[
                                "tanggal"=>$item->GetTanggal()?->toFormat(FormatDate::LDFY),
                                "masuk"=>$item->GetAbsenMasuk()?->toFormat(FormatDate::HIS),
                                "keluar"=>$item->GetAbsenKeluar()?->toFormat(FormatDate::HIS),
                                "catatan_telat"=>$item->GetCatatanTelat(),
                                "catatan_pulang"=>$item->GetCatatanPulang(),
                            ],
                        };
                    });

        $table = DataTables::of($datas)
        ->addIndexColumn()
        ->make(true);

        return $table;
    }
}
