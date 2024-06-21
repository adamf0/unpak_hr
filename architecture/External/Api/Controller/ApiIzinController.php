<?php

namespace Architecture\External\Api\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Izin\Update\ApprovalIzinCommand;
use Exception;
use Illuminate\Http\Request;

class ApiIzinController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function reject(Request $request){
        try {
            if(empty($request->id)) throw new Exception("invalid reject izin");

            $this->commandBus->dispatch(new ApprovalIzinCommand($request->id,$request->level=="sdm"? "tolak sdm":"tolak atasan",$request->catatan));
            
            return response()->json([
                "status"=>"ok",
                "message"=>"berhasil tolak izin",
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
