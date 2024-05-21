<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Presensi\FirstData\GetPresensiByNIDNQuery;
use Architecture\Domain\Enum\TypeNotif;

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
            $commandPresensi = match(true){
                Session::get('nidn')!=null => new GetPresensiByNIDNQuery(Session::get('nidn')),
                Session::get('nip')!=null => new GetPresensiByNIDNQuery(Session::get('nip')),
                default => null,
            };
            $presensi = $commandPresensi? $this->queryBus->ask($commandPresensi):null;

            return view('dashboard.index',[
                "presensi"=>$presensi
            ]);
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
        }
    }
}
