<?php

namespace Architecture\External\Api\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\SPPD\Update\RejectSPPDCommand;
use Exception;
use Illuminate\Http\Request;

class ApiSPPDController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function reject(Request $request){
        try {
            if(empty($request->id)) throw new Exception("invalid reject sppd");
            if(!in_array($request->level,['sdm','warek'])) throw new Exception("selain SDM dan Warek tidak dapat tolak sppd");

            $status = match($request->level){
                "warek"=>"tolak warek",
                "sdm"=>"tolak sdm",
                default=>null,
            };
            $this->commandBus->dispatch(new RejectSPPDCommand($request->id,$request->catatan,$request->pic,$status));
            
            return response()->json([
                "status"=>"ok",
                "message"=>"berhasil tolak SPPD",
                "data"=>null,
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
