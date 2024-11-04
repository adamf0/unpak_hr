<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SlipGajiController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function Index(){
        $nidn = Session::get('nidn');
        $nip  = Session::get('nip');

        return view('slip_gaji.index',['nip'=>$nip,'nidn'=>$nidn]);
    }
}
