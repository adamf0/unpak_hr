<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Presensi\FirstData\GetPresensiByNIDNQuery;
use Architecture\Application\Presensi\FirstData\GetPresensiByNIPQuery;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\External\Persistance\ORM\Absensi;
use Exception;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function Index(){
        try {
            if(!is_null(Session::get('nidn'))){
                $presensi = $this->queryBus->ask(new GetPresensiByNIDNQuery(Session::get('nidn')));
                return view('dashboard.index',[
                    "presensi"=>$presensi
                ]);
            } else if(!is_null(Session::get('nip'))){
                $presensi = $this->queryBus->ask(new GetPresensiByNIPQuery(Session::get('nip')));
                return view('dashboard.index',[
                    "presensi"=>$presensi
                ]);
            }

            $list_absen = Absensi::with(['Pegawai','Dosen'])->where('tanggal',now())->get();
            return view('dashboard.index',[
                "presensi"=>null,
                "list_absen"=>$list_absen,
            ]);
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
        }
    }
}
