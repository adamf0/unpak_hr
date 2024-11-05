<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\External\Persistance\ORM\SlipGaji;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Architecture\External\Persistance\ORM\EPribadi;

class SlipGajiController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function Index(){
        $nidn = Session::get('nidn');
        $nip  = Session::get('nip');
        if($nip==null){
            $nip  = EPribadi::where('nidn',$nidn)->first();
            $nip = $nip==null? null:$nip->nip;
        }

        $list_tahun = SlipGaji::select('tahun')
        ->where('nip',$nip)
        ->distinct()
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->tahun,
                'text' => $item->tahun,
            ];
        });

        return view('slip_gaji.index',['tahun'=>json_encode($list_tahun),'nip'=>$nip,'nidn'=>$nidn]);
    }
}
