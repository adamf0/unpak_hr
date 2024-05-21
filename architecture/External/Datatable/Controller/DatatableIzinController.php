<?php

namespace Architecture\External\Datatable\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Izin\List\GetAllIzinQuery;
use Architecture\Domain\Enum\FormatDate;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;

class DatatableIzinController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function index(Request $request){
        $nidn = $request->has('nidn')? $request->query('nidn'):null;
        $nip = $request->has('nip')? $request->query('nip'):null;
        $level = $request->has('level')? $request->query('level'):null;
        $q = new GetAllIzinQuery($nidn,$nip);
        // $q->SetOffset($request->get('start')??null)->SetLimit($request->get('length')??null);
        
        $listIzin = $this->queryBus->ask($q);
        $listIzin = $listIzin->map(fn($item)=>(object)[
            "id" => $item->GetId(),
            "nidn" => $item->GetNIDN(),
            "nip" => $item->GetNIP(),
            "tanggal_pengajuan" => $item->GetTanggalPengajuan()->toFormat(FormatDate::LDFY),
            "tujuan" => $item->GetTujuan(),
            "jenis_izin" => $item->GetJenisIzin()?->GetNama(),
            "dokumen" => $item->GetDokumen(),
            "catatan" => $item->GetCatatan(),
            "status" => $item->GetStatus(),
        ]);
        
        return DataTables::of($listIzin)
        ->addIndexColumn()
        ->addColumn('action', function ($row) use($level){
            $render = '';
            if(in_array($level,['dosen','pegawai']) && in_array($row->status, ['menunggu','tolak'])){
                $render = '
                <a href="'.route('izin.edit',['id'=>$row->id]).'" class="btn btn-warning"><i class="bi bi-pencil-square"></i></a>
                <a href="'.route('izin.delete',['id'=>$row->id]).'" class="btn btn-danger"><i class="bi bi-trash"></i></a>
                ';
            }
            else if($level=="sdm"){
                $render = '
                <a href="'.route('izin.approval',['id'=>$row->id,'type'=>'terima']).'" class="btn btn-success"><i class="bi bi-check-lg"></i></a>
                <a href="#" class="btn btn-danger btn-reject"><i class="bi bi-x-lg"></i></a>
                ';
            }
            return $render;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
