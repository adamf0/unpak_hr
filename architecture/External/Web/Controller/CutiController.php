<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Abstractions\Pattern\OptionFileDefault;
use Architecture\Application\Cuti\Count\CountCutiQuery;
use Architecture\Application\Cuti\Create\CreateCutiCommand;
use Architecture\Application\Cuti\Delete\DeleteCutiCommand;
use Architecture\Application\Cuti\FirstData\GetCutiQuery;
use Architecture\Application\Cuti\Update\ApprovalCutiCommand;
use Architecture\Application\Cuti\Update\UpdateCutiCommand;
use Architecture\Application\Izin\Count\CountIzinQuery;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\FolderX;
use Architecture\Domain\Entity\JenisCutiReferensi;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\Domain\RuleValidationRequest\Cuti\CreateCutiRuleReq;
use Architecture\Domain\RuleValidationRequest\Cuti\DeleteCutiRuleReq;
use Architecture\Domain\RuleValidationRequest\Cuti\UpdateCutiRuleReq;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\Cuti;
use Architecture\External\Port\FileSystem;
use Architecture\External\Port\PdfX;
use Architecture\Shared\Creational\FileManager;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $validator      = validator($request->all(), CreateCutiRuleReq::create(Session::get("nidn"), Session::get("nip")));

            if(count($validator->errors())){
                return redirect()->route('cuti.create')->withInput()->withErrors($validator->errors()->toArray());    
            } 
            $waitingCuti = $this->queryBus->ask(new CountCutiQuery(
                Session::get("nidn"),
                Session::get("nip"),
                "menunggu",
            ));
            if($waitingCuti>0){
                throw new Exception("pengajuan di tolak karena masih ada pengajuan cuti yg masih menunggu persetujuan SDM");
            }
            $waitingIzin = $this->queryBus->ask(new CountIzinQuery(
                Session::get("nidn"),
                Session::get("nip"),
                "menunggu",
            ));
            if($waitingIzin>0){
                throw new Exception("pengajuan di tolak karena masih ada pengajuan izin yg masih menunggu persetujuan SDM");
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
    public function approval($id,$type){
        try {
            if(!in_array($type,["terima","tolak"])) throw new Exception("command invalid");

            $this->commandBus->dispatch(new ApprovalCutiCommand($id,$type,null,Session::get('id')));
            Session::flash(TypeNotif::Create->val(), "berhasil $type cuti");

            return redirect()->route('cuti.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('cuti.index');
        }
    }
    public function export(Request $request){
        try {
            $nidn           = $request->has('nidn')? $request->query('nidn'):null;
            $nip            = $request->has('nip')? $request->query('nip'):null;
            $jenis_cuti     = $request->has('jenis_cuti')? $request->query('jenis_cuti'):null;
            $status         = $request->has('status')? $request->query('status'):null;
            $tanggal_mulai  = $request->has('tanggal_mulai')? $request->query('tanggal_mulai'):null;
            $tanggal_akhir  = $request->has('tanggal_akhir')? $request->query('tanggal_akhir'):null;
            $type_export    = $request->has('type_export')? $request->query('type_export'):null;

            $file_name = "cuti";
            $cuti = Cuti::with(['JenisCuti','Dosen','Pegawai']);

            if(!is_null($nidn) && !is_null($nip)){
                throw new Exception("harus salah satu antara nidn dan nip");
            } else if(is_null($type_export)){
                throw new Exception("belum pilih cetak sebagai apa");
            }

            if($nidn){
                $cuti->where('nidn',$nidn);
                $file_name = $file_name."_$nidn";
            }
            if($nip){
                $cuti->where('nip',$nip);
                $file_name = $file_name."_$nip";
            }
            if($jenis_cuti){
                $cuti->where('id_jenis_cuti',$jenis_cuti);
                $file_name = $file_name."_$jenis_cuti";
            }
            if($status){
                $cuti->where('status',$status);
                $file_name = $file_name."_$status";
            }
            if($tanggal_mulai && is_null($tanggal_akhir)){
                $cuti->where('tanggal_mulai',$tanggal_mulai);
                $file_name = $file_name."_$tanggal_mulai";
            }
            else if($tanggal_akhir && is_null($tanggal_mulai)){
                $cuti->where('tanggal_akhir',$tanggal_akhir);
                $file_name = $file_name."_$tanggal_akhir";
            } else if($tanggal_mulai && $tanggal_akhir){
                $cuti->whereBetween('tanggal_mulai', [$tanggal_mulai, $tanggal_akhir])
                    ->whereBetween('tanggal_akhir', [$tanggal_mulai, $tanggal_akhir]);

                $file_name = $file_name."_$tanggal_mulai-$tanggal_akhir";
            }
            // $cuti = $cuti->leftJoin('jenis_cuti','cuti.id_jenis_cuti',"=","jenis_cuti.id")
            //             ->leftJoin(DB::raw(config('database.connections.simak.database') . '.m_dosen'), 'cuti.nidn', '=', 'm_dosen.nidn')
            //             ->leftJoin(DB::raw(config('database.connections.simpeg.database') . '.n_pribadi'), 'cuti.nip', '=', 'n_pribadi.nip')
            //             ->select('cuti.*','jenis_cuti.nama as nama_cuti', 'm_dosen.nama_dosen as nama_dosen','n_pribadi.nama as nama_pegawai');
            $list_cuti = $cuti->get();

            if($type_export=="pdf"){
                $file = PdfX::From(
                    "template.export_cuti", 
                    [
                        "list_cuti"=>$list_cuti
                    ], 
                    FolderX::FromPath(public_path('export/pdf')), 
                    "$file_name.pdf"
                );
            } else{
                throw new Exception("export type '$type_export' not implementation");
            }
    
            return FileManager::StreamFile($file);

        } catch (Exception $e) {
            // throw $e;
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('cuti.index');
        }
    }
}
