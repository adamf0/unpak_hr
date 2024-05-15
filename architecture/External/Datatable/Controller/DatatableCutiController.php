<?php

namespace Architecture\External\Datatable\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Cuti\List\GetAllCutiQuery;
use Architecture\Domain\Enum\FormatDate;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;

class DatatableCutiController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function index(Request $request){
        $q = new GetAllCutiQuery();
        // $q->SetOffset($request->get('start')??null)->SetLimit($request->get('length')??null);
        
        $listCuti = $this->queryBus->ask($q);
        $listCuti = $listCuti->map(fn($item)=>(object)[
            "id"=>$item->GetId(),
            "nidn"=>$item->GetNIDN(),
            "nip"=>$item->GetNIP(),
            "jenis_cuti"=>$item->GetJenisCuti()?->GetNama()??"-",
            "lama_cuti"=>$item->GetLamaCuti(),
            "tanggal_mulai"=>$item->GetTanggalMulai()->toFormat(FormatDate::LDFY),
            "tanggal_akhir"=>$item->GetTanggalAkhir()?->toFormat(FormatDate::LDFY),
            "tujuan"=>$item->GetTujuan(),
            "dokumen"=>$item->GetDokumen(),
            "status"=>$item->GetStatus(),
            "catatan"=>$item->GetCatatan(),
        ]);
        
        return DataTables::of($listCuti)
        ->addIndexColumn()
        ->addColumn('tanggal_awal_akhir', function ($row) {
            $render = $row->tanggal_akhir==null || $row->tanggal_akhir==$row->tanggal_mulai? $row->tanggal_mulai : "{$row->tanggal_mulai} - {$row->tanggal_akhir}";
            return $render;
        })
        ->addColumn('tanggal_awal_akhir', function ($row) {
            $render = $row->tanggal_akhir==null || $row->tanggal_akhir==$row->tanggal_mulai? $row->tanggal_mulai : "{$row->tanggal_mulai} - {$row->tanggal_akhir}";
            return $render;
        })
        ->addColumn('action', function ($row) {
            $render = '
            <a href="'.route('cuti.edit',['id'=>$row->id]).'" class="btn btn-warning"><i class="bi bi-pencil-square"></i></a>
            <a href="'.route('cuti.delete',['id'=>$row->id]).'" class="btn btn-danger"><i class="bi bi-trash"></i></a>
            ';
            return $render;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
