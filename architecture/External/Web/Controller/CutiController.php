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
use Architecture\Domain\Entity\DosenReferensi;
use Architecture\Domain\Entity\FolderX;
use Architecture\Domain\Entity\JenisCutiReferensi;
use Architecture\Domain\Entity\PegawaiReferensi;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\Domain\RuleValidationRequest\Cuti\CreateCutiRuleReq;
use Architecture\Domain\RuleValidationRequest\Cuti\DeleteCutiRuleReq;
use Architecture\Domain\RuleValidationRequest\Cuti\UpdateCutiRuleReq;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\Cuti;
use Architecture\External\Port\ExportCutiXls;
use Architecture\External\Port\FileSystem;
use Architecture\External\Port\PdfX;
use Architecture\Shared\Creational\FileManager;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class CutiController extends Controller
{
    public $disk_cuti = "dokumen_cuti";
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function index($type=null){
        return view('cuti.index',['type'=>$type,'verifikasi'=>Session::get('levelActive')=="sdm"]);
    }
    public function verifikasi(){
        return view('cuti.index',['type'=>null,'verifikasi'=>true]);
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
                Session::has("nidn")? Creator::buildDosen(DosenReferensi::make(Session::get("nidn"))):null,
                Session::has("nip")? Creator::buildPegawai(PegawaiReferensi::make(Session::get("nip"))):null,
                Creator::buildJenisCuti(JenisCutiReferensi::make(
                    $request->get("jenis_cuti")
                )),
                $request->get("lama_cuti"),
                new Date($request->get("tanggal_mulai")),
                $request->get("tanggal_akhir")!=""? new Date($request->get("tanggal_akhir")):null,
                $request->get("tujuan"),
                $file,
                $request->has("verifikasi")? Creator::buildPegawai(PegawaiReferensi::make($request->get("verifikasi"))):null,
                "menunggu",
            ));
            Session::flash(TypeNotif::Create->val(), "berhasil tambah data");

            return redirect()->route('cuti.index2',['type'=>Session::get('levelActive')=="pegawai"? "tendik":"dosen"]);
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
                Session::has("nidn")? Creator::buildDosen(DosenReferensi::make(Session::get("nidn"))):null,
                Session::has("nip")? Creator::buildPegawai(PegawaiReferensi::make(Session::get("nip"))):null,
                Creator::buildJenisCuti(JenisCutiReferensi::make(
                    $request->get("jenis_cuti")
                )),
                $request->get("lama_cuti"),
                new Date($request->get("tanggal_mulai")),
                $request->get("tanggal_akhir")!=""? new Date($request->get("tanggal_akhir")):null,
                $request->get("tujuan"),
                $file,
                $request->has("verifikasi")? Creator::buildPegawai(PegawaiReferensi::make($request->get("verifikasi"))):null,
                "menunggu",
            ));
            Session::flash(TypeNotif::Update->val(), "berhasil ubah data");

            return redirect()->route('cuti.index2',['type'=>Session::get('levelActive')=="pegawai"? "tendik":"dosen"]);
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
        $cuti = $this->queryBus->ask(new GetCutiQuery($id));
        $redirect = match(true){
            Session::get('levelActive')=="sdm" && !is_null($cuti->GetDosen()) => redirect()->route('cuti.index2',['type'=>'dosen']),
            Session::get('levelActive')=="sdm" && !is_null($cuti->GetPegawai()) => redirect()->route('cuti.index2',['type'=>'tendik']),
            in_array(Session::get('levelActive'), ["dosen","pegawai"]) => redirect()->route('cuti.index2',['type'=>'verifikasi']),
            default=>redirect()->route('cuti.index'),
        };

        try {
            // if(!in_array($type,["terima","tolak"])) throw new Exception("command invalid");

            $this->commandBus->dispatch(new ApprovalCutiCommand($id,$type,null));
            Session::flash(TypeNotif::Create->val(), "berhasil $type cuti");

            return $redirect;
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return $redirect;
        }
    }
    public function export(Request $request){
        try {
            $nama           = $request->has('nama')? $request->query('nama'):null;
            $type           = $request->has('type')? $request->query('type'):null;
            $jenis_cuti     = $request->has('jenis_cuti')? $request->query('jenis_cuti'):null;
            $status         = $request->has('status')? $request->query('status'):null;
            $tanggal_mulai  = $request->has('tanggal_mulai')? $request->query('tanggal_mulai'):null;
            $tanggal_akhir  = $request->has('tanggal_akhir')? $request->query('tanggal_akhir'):null;
            $type_export    = $request->has('type_export')? $request->query('type_export'):null;

            $file_name = "cuti";
            $cuti = Cuti::with(['JenisCuti','Dosen','Pegawai']);

            if(is_null($type_export)){
                throw new Exception("belum pilih cetak sebagai apa");
            }

            if($type=="dosen" && !is_null($nama)){
                $cuti->where('nidn',$nama);
                $file_name = $file_name."_$nama";
            } else if($type=="dosen" && is_null($nama)){
                $cuti->whereNotNull('nidn');
                $file_name = $file_name."_semua-nama";
            } else if($type=="tendik" && !is_null($nama)){
                $cuti->where('nip',$nama);
                $file_name = $file_name."_$nama";
            } else if($type=="tendik" && is_null($nama)){
                $cuti->whereNotNull('nip');
                $file_name = $file_name."_semua-nama";
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
                return FileManager::StreamFile($file);
            } else{
                $list_cuti = $list_cuti->reduce(function($carry,$item){
                    $carry[] = [
                        'nama'=>match(true){
                            !is_null($item->Dosen) && is_null($item->Pegawai)=>$item->Dosen->nama_dosen,
                            is_null($item->Dosen) && !is_null($item->Pegawai)=>$item->Pegawai->nama,
                            default=>"NA"
                        },
                        'tanggal_cuti' =>(empty($item->tanggal_mulai)? "":Carbon::parse($item->tanggal_mulai)->setTimezone('Asia/Jakarta')->format("d F Y"))." - ".(empty($item->tanggal_akhir)? "":Carbon::parse($item->tanggal_akhir)->setTimezone('Asia/Jakarta')->format("d F Y")),
                        'lama_cuti' =>$item->lama_cuti,
                        'jenis_cuti'=>$item->JenisCuti?->nama,
                        'tujuan'=>$item->tujuan
                    ];

                    return $carry;
                });
                return Excel::download(new ExportCutiXls(collect($list_cuti), ['nama','tanggal cuti','lama cuti (hari)','jenis cuti','tujuan']), "$file_name.xlsx");
            }
        } catch (Exception $e) {
            // throw $e;
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return empty($type)? redirect()->route('cuti.index'):redirect()->route('cuti.index2',['type'=>$type]);
        }
    }
}
