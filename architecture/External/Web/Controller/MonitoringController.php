<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Domain\Enum\TypeNotif;
use Exception;
use Illuminate\Support\Facades\Session;

class MonitoringController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function Index(){
        try {
            // $list_absen = Absensi::with(['Pegawai','Dosen'])->where('tanggal',date('Y-m-d'))->orderBy('absen_masuk','DESC')->get();
            return view('monitoring.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
        }
    }
}
