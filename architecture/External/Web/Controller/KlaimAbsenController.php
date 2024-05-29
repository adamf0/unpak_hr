<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Abstractions\Pattern\OptionFileDefault;
use Architecture\Application\KlaimAbsen\Create\CreateKlaimAbsenCommand;
use Architecture\Application\KlaimAbsen\Delete\DeleteKlaimAbsenCommand;
use Architecture\Application\KlaimAbsen\FirstData\GetKlaimAbsenQuery;
use Architecture\Application\KlaimAbsen\Update\ApprovalKlaimAbsenCommand;
use Architecture\Application\KlaimAbsen\Update\UpdateKlaimAbsenCommand;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\DosenReferensi;
use Architecture\Domain\Entity\PegawaiReferensi;
use Architecture\Domain\Entity\PresensiReferensi;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\Domain\RuleValidationRequest\KlaimAbsen\CreateKlaimAbsenRuleReq;
use Architecture\Domain\RuleValidationRequest\KlaimAbsen\DeleteKlaimAbsenRuleReq;
use Architecture\Domain\RuleValidationRequest\KlaimAbsen\UpdateKlaimAbsenRuleReq;
use Architecture\External\Port\FileSystem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class KlaimAbsenController extends Controller
{
    private $disk_klaim_absen = "dokumen_klaim_absen";
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function Index(){
        return view('klaim_absen.index');
    }

    public function create(){
        return view('klaim_absen.create');
    }
    public function store(Request $request){
        try {
            $validator      = validator($request->all(), CreateKlaimAbsenRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('klaim_absen.create')->withInput()->withErrors($validator->errors()->toArray());    
            } 
            
            $file = null;
            if($request->has("dokumen") && $request->file("dokumen")!=null){
                $fileSystem = new FileSystem(new OptionFileDefault($request->file("dokumen"),$this->disk_klaim_absen));
                $file = $fileSystem->storeFileWithReplaceFileAndReturnFileLocation();
            }

            $this->commandBus->dispatch(new CreateKlaimAbsenCommand(
                Session::has("nidn")? Creator::buildDosen(DosenReferensi::make(Session::get("nidn"))):null,
                Session::has("nip")? Creator::buildPegawai(PegawaiReferensi::make(Session::get("nip"))):null,
                Creator::buildPresensi(PresensiReferensi::make($request->get("tanggal_absen"))),
                $request->get("jam_masuk"),
                $request->get("jam_keluar"),
                $request->get("tujuan"),
                $file,
            ));
            Session::flash(TypeNotif::Create->val(), "berhasil tambah data");

            return redirect()->route('klaim_absen.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('klaim_absen.create')->withInput();
        }
    }
    public function edit($id){
        try {
            $KlaimAbsen = $this->queryBus->ask(new GetKlaimAbsenQuery($id));
            
            return view('klaim_absen.edit',[
                "KlaimAbsen"=>$KlaimAbsen
            ]);
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('klaim_absen.index');
        }
    }
    public function update(Request $request){
        try {
            if(!$request->has('dokumen'))
                $request = request()->merge(["dokumen"=> "0"]);

            $validator      = validator($request->all(), UpdateKlaimAbsenRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('klaim_absen.edit',["id"=>$request->get("id")])->withInput()->withErrors($validator->errors()->toArray());    
            } 
            
            $klaim_absen = $this->queryBus->ask(new GetKlaimAbsenQuery($request->get("id")));
            if($request->has("dokumen") && $request->file("dokumen")!=null){
                $klaim_absen = $this->queryBus->ask(new GetKlaimAbsenQuery($request->get("id")));
                if(Storage::disk($this->disk_klaim_absen)->exists($klaim_absen->GetDokumen()->getFileName())){
                    Storage::disk($this->disk_klaim_absen)->delete($klaim_absen->GetDokumen()->getFileName());
                }
                $fileSystem = new FileSystem(new OptionFileDefault($request->file("dokumen"),$this->disk_klaim_absen));
                $file = $fileSystem->storeFileWithReplaceFileAndReturnFileLocation();
            } else{
                $file = $klaim_absen->GetDokumen();
            }

            $this->commandBus->dispatch(new UpdateKlaimAbsenCommand(
                $request->get('id'), 
                Session::has("nidn")? Creator::buildDosen(DosenReferensi::make(Session::get("nidn"))):null,
                Session::has("nip")? Creator::buildPegawai(PegawaiReferensi::make(Session::get("nip"))):null,
                Creator::buildPresensi(PresensiReferensi::make($request->get("tanggal_absen"))),
                $request->get("jam_masuk"),
                $request->get("jam_keluar"),
                $request->get("tujuan"),
                $file,
            ));
            Session::flash(TypeNotif::Update->val(), "berhasil ubah data");

            return redirect()->route('klaim_absen.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('klaim_absen.edit',["id"=>$request->get('id')])->withInput();
        }
    }
    public function delete($id){
        $request = request()->merge(["id"=> $id]);
        try {
            $validator      = validator($request->all(), DeleteKlaimAbsenRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('klaim_absen.index')->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new DeleteKlaimAbsenCommand($id));
            Session::flash(TypeNotif::Create->val(), "berhasil hapus data");

            return redirect()->route('klaim_absen.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('klaim_absen.index');
        }
    }
    public function approval($id,$type){
        try {
            if(!in_array($type,["terima","tolak"])) throw new Exception("command invalid");

            $this->commandBus->dispatch(new ApprovalKlaimAbsenCommand($id,$type,null,Session::get('id')));
            Session::flash(TypeNotif::Create->val(), "berhasil $type klaim absen");

            return redirect()->route('klaim_absen.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('klaim_absen.index');
        }
    }
}
