<?php

namespace Architecture\External\Api\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Abstractions\Pattern\OptionFileDefault;
use Architecture\Application\SPPD\FirstData\GetSPPDQuery;
use Architecture\Application\SPPD\Update\ApprovalSPPDCommand;
use Architecture\Application\SPPD\Update\RejectSPPDCommand;
use Architecture\External\Port\FileSystem;
use Exception;
use Illuminate\Http\Request;

class ApiSPPDController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function approval(Request $request){
        $sppd = $this->queryBus->ask(new GetSPPDQuery($request->id));
        $redirect = match(true){
            !is_null($sppd->GetDosen())=>redirect()->route('sppd.index2',['type'=>'dosen']),
            !is_null($sppd->GetPegawai())=>redirect()->route('sppd.index2',['type'=>'tendik']),
            default=>redirect()->route('sppd.index'),
        };

        try {
            if(empty($request->id)) throw new Exception("invalid reject sppd");
            if(!in_array($request->level,['sdm','warek'])) throw new Exception("selain SDM dan Warek tidak dapat approval sppd");

            $fileSystem = new FileSystem(new OptionFileDefault($request->file("dokumen_anggaran_biaya"),"dokumen_anggaran"));
            $file = $fileSystem->storeFileWithReplaceFileAndReturnFileLocation();

            $this->commandBus->dispatch(new ApprovalSPPDCommand($request->id,$request->level=="warek"? "menunggu verifikasi sdm":"terima sdm",$file, $request->level=="warek"? null:$request->pic));
            return response()->json([
                "status"=>"ok",
                "message"=>"berhasil terima SPPD",
                "data"=>null,
            ]);
            return $redirect;
        } catch (Exception $e) {
            return response()->json([
                "status"=>"fail",
                "message"=>"gagal terima SPPD",
                "data"=>null,
                "log"=>$e->getMessage()
            ]);
            return $redirect;
        }
    }
    public function reject(Request $request){
        try {
            if(empty($request->id)) throw new Exception("invalid reject sppd");
            if(!in_array($request->level,['sdm','warek'])) throw new Exception("selain SDM dan Warek tidak dapat tolak sppd");

            $this->commandBus->dispatch(new RejectSPPDCommand($request->id,$request->catatan,$request->level=="warek"? "tolak warek":"tolak sdm"));
            
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
