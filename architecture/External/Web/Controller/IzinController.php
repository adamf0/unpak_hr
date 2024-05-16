<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Abstractions\Pattern\OptionFileDefault;
use Architecture\Application\Izin\Create\CreateIzinCommand;
use Architecture\Application\Izin\Delete\DeleteIzinCommand;
use Architecture\Application\Izin\FirstData\GetIzinQuery;
use Architecture\Application\Izin\Update\ApprovalIzinCommand;
use Architecture\Application\Izin\Update\UpdateIzinCommand;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\JenisIzinReferensi;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\Domain\RuleValidationRequest\Izin\CreateIzinRuleReq;
use Architecture\Domain\RuleValidationRequest\Izin\DeleteIzinRuleReq;
use Architecture\Domain\RuleValidationRequest\Izin\UpdateIzinRuleReq;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Port\FileSystem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class IzinController extends Controller
{
    public $disk_izin = "dokumen_izin";
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function Index(){
        return view('izin.index');
    }

    public function create(){
        return view('izin.create');
    }
    public function store(Request $request){
        try {
            $validator      = validator($request->all(), CreateIzinRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('izin.create')->withInput()->withErrors($validator->errors()->toArray());    
            } 
            
            $file = null;
            if($request->has("dokumen") && $request->file("dokumen")!=null){
                $fileSystem = new FileSystem(new OptionFileDefault($request->file("dokumen"),$this->disk_izin));
                $file = $fileSystem->storeFileWithReplaceFileAndReturnFileLocation();
            }

            $this->commandBus->dispatch(new CreateIzinCommand(
                Session::get("nidn"),
                Session::get("nip"),
                new Date($request->get("tanggal_pengajuan")),
                $request->get("tujuan"),
                Creator::buildJenisIzin(JenisIzinReferensi::make($request->get("jenis_izin"))),
                $file,
                $request->get("catatan"),
                "menunggu",
            ));
            Session::flash(TypeNotif::Create->val(), "berhasil tambah data");

            return redirect()->route('izin.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('izin.create')->withInput();
        }
    }
    public function edit($id){
        try {
            $Izin = $this->queryBus->ask(new GetIzinQuery($id));
            
            return view('izin.edit',[
                "Izin"=>$Izin
            ]);
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('izin.index');
        }
    }
    public function update(Request $request){
        try {
            if(!$request->has('dokumen'))
                $request = request()->merge(["dokumen"=> "0"]);

            $validator      = validator($request->all(), UpdateIzinRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('izin.edit',["id"=>$request->get("id")])->withInput()->withErrors($validator->errors()->toArray());    
            } 

            $izin = $this->queryBus->ask(new GetIzinQuery($request->get("id")));
            if($request->has("dokumen") && $request->file("dokumen")!=null){
                $izin = $this->queryBus->ask(new GetIzinQuery($request->get("id")));
                if(Storage::disk($this->disk_izin)->exists($izin->GetDokumen()->getFileName())){
                    Storage::disk($this->disk_izin)->delete($izin->GetDokumen()->getFileName());
                }
                $fileSystem = new FileSystem(new OptionFileDefault($request->file("dokumen"),"dokumen_izin"));
                $file = $fileSystem->storeFileWithReplaceFileAndReturnFileLocation();
            } else{
                $file = $izin->GetDokumen();
            }

            $this->commandBus->dispatch(new UpdateIzinCommand(
                $request->get('id'), 
                Session::get("nidn"),
                Session::get("nip"),
                new Date($request->get("tanggal_pengajuan")),
                $request->get("tujuan"),
                Creator::buildJenisIzin(JenisIzinReferensi::make($request->get("jenis_izin"))),
                $file,
                $request->get("catatan"),
                $izin->GetStatus(),
            ));
            Session::flash(TypeNotif::Update->val(), "berhasil ubah data");

            return redirect()->route('izin.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('izin.edit',["id"=>$request->get('id')])->withInput();
        }
    }
    public function delete($id){
        $request = request()->merge(["id"=> $id]);
        try {
            $validator      = validator($request->all(), DeleteIzinRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('izin.index')->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new DeleteIzinCommand($id));
            Session::flash(TypeNotif::Create->val(), "berhasil hapus data");

            return redirect()->route('izin.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('izin.index');
        }
    }
    public function approval($id,$type){
        try {
            if(!in_array($type,["terima","tolak"])) throw new Exception("command invalid");

            $this->commandBus->dispatch(new ApprovalIzinCommand($id,$type));
            Session::flash(TypeNotif::Create->val(), "berhasil $type izin");

            return redirect()->route('izin.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('izin.index');
        }
    }
}
