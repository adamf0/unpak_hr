<?php

namespace Architecture\External\Api\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Dosen\GetInfoDosenQuery;
use Architecture\Application\Pegawai\FirstData\GetInfoPegawaiQuery;
use Architecture\Shared\TypeData;
use Exception;
use Illuminate\Http\Request;

class ApiInfoController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function index(Request $request){
        try {
            $dosen = $this->queryBus->ask(new GetInfoDosenQuery($request->nidn, TypeData::Default));
            $pegawai = $this->queryBus->ask(new GetInfoPegawaiQuery($request->nip, TypeData::Default));

            if(!is_null($dosen)){
                $dosen->nidn = $dosen->NIDN;
            }
            
            return response()->json([
                "status"=>"ok",
                "message"=>"",
                "data"=>[
                    "dosen"=>$dosen,
                    "pegawai"=>$pegawai,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status"=>"fail",
                "message"=>"data tidak ditemukan",
                "data"=>null,
                "log"=>$e->getMessage()
            ]);
        }
    }
}
