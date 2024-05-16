<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Abstractions\Pattern\OptionFileDefault;
use Architecture\Application\Cuti\Create\CreateCutiCommand;
use Architecture\Application\Cuti\Delete\DeleteCutiCommand;
use Architecture\Application\Cuti\FirstData\GetCutiQuery;
use Architecture\Application\Cuti\Update\UpdateCutiCommand;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\JenisCutiReferensi;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\Domain\RuleValidationRequest\Cuti\CreateCutiRuleReq;
use Architecture\Domain\RuleValidationRequest\Cuti\DeleteCutiRuleReq;
use Architecture\Domain\RuleValidationRequest\Cuti\UpdateCutiRuleReq;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Port\FileSystem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class CutiController extends Controller
{
    public $disk_cuti = "dokumen_cuti";
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function Index(){
        return view('cuti.index');
    }

    public function create(){
        return view('cuti.create');
    }
    public function store(Request $request){
        try {
            $validator      = validator($request->all(), CreateCutiRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('cuti.create')->withInput()->withErrors($validator->errors()->toArray());    
            } 
            
            $file = null;
            if($request->has("dokumen") && $request->file("dokumen")!=null){
                $fileSystem = new FileSystem(new OptionFileDefault($request->file("dokumen"),$this->disk_cuti));
                $file = $fileSystem->storeFileWithReplaceFileAndReturnFileLocation();
            }

            $this->commandBus->dispatch(new CreateCutiCommand(
                Session::get("nidn")??null,
                Session::get("nip")??null,
                Creator::buildJenisCuti(JenisCutiReferensi::make(
                    $request->get("jenis_cuti")
                )),
                $request->get("lama_cuti"),
                new Date($request->get("tanggal_mulai")),
                $request->get("tanggal_akhir")!=""? new Date($request->get("tanggal_akhir")):null,
                $request->get("tujuan"),
                $file,
                $request->get("catatan"),
                "menunggu",
            ));
            Session::flash(TypeNotif::Create->val(), "berhasil tambah data");

            return redirect()->route('cuti.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('cuti.create')->withInput();
        }
    }
    public function edit($id){
        try {
            $Cuti = $this->queryBus->ask(new GetCutiQuery($id));
            
            return view('cuti.edit',[
                "Cuti"=>$Cuti
            ]);
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('cuti.index');
        }
    }
    public function update(Request $request){
        try {
            $validator      = validator($request->all(), UpdateCutiRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('cuti.edit',["id"=>$request->get("id")])->withInput()->withErrors($validator->errors()->toArray());    
            } 
            
            $cuti = $this->queryBus->ask(new GetCutiQuery($request->get("id")));
            if($request->has("dokumen") && $request->file("dokumen")!=null){
                $cuti = $this->queryBus->ask(new GetCutiQuery($request->get("id")));
                if(Storage::disk($this->disk_cuti)->exists($cuti->GetDokumen()->getFileName())){
                    Storage::disk($this->disk_cuti)->delete($cuti->GetDokumen()->getFileName());
                }
                $fileSystem = new FileSystem(new OptionFileDefault($request->file("dokumen"),"dokumen_cuti"));
                $file = $fileSystem->storeFileWithReplaceFileAndReturnFileLocation();
            } else{
                $file = $cuti->GetDokumen();
            }

            $this->commandBus->dispatch(new UpdateCutiCommand(
                $request->get("id"),
                Session::get("nidn")??null,
                Session::get("nip")??null,
                Creator::buildJenisCuti(JenisCutiReferensi::make(
                    $request->get("jenis_cuti")
                )),
                $request->get("lama_cuti"),
                new Date($request->get("tanggal_mulai")),
                $request->get("tanggal_akhir")!=""? new Date($request->get("tanggal_akhir")):null,
                $request->get("tujuan"),
                $file,
                $request->get("catatan"),
                "menunggu",
            ));
            Session::flash(TypeNotif::Update->val(), "berhasil ubah data");

            return redirect()->route('cuti.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('cuti.edit',["id"=>$request->get('id')])->withInput();
        }
    }
    public function delete($id){
        $request = request()->merge(["id"=> $id]);
        try {
            $validator      = validator($request->all(), DeleteCutiRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('cuti.index')->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new DeleteCutiCommand($id));
            Session::flash(TypeNotif::Create->val(), "berhasil hapus data");

            return redirect()->route('cuti.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('cuti.index');
        }
    }
}
