<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Abstractions\Pattern\OptionFileDefault;
use Architecture\Application\Cuti\Count\CountCutiQuery;
use Architecture\Application\Izin\Count\CountIzinQuery;
use Architecture\Application\Izin\Create\CreateIzinCommand;
use Architecture\Application\Izin\Delete\DeleteIzinCommand;
use Architecture\Application\Izin\FirstData\GetIzinQuery;
use Architecture\Application\Izin\Update\ApprovalIzinCommand;
use Architecture\Application\Izin\Update\UpdateIzinCommand;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\DosenReferensi;
use Architecture\Domain\Entity\FolderX;
use Architecture\Domain\Entity\JenisIzinReferensi;
use Architecture\Domain\Entity\PegawaiReferensi;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\Domain\RuleValidationRequest\Izin\CreateIzinRuleReq;
use Architecture\Domain\RuleValidationRequest\Izin\DeleteIzinRuleReq;
use Architecture\Domain\RuleValidationRequest\Izin\UpdateIzinRuleReq;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Persistance\ORM\Izin;
use Architecture\External\Port\ExportIzinXls;
use Architecture\External\Port\FileSystem;
use Architecture\External\Port\PdfX;
use Architecture\Shared\Creational\FileManager;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class IzinController extends Controller
{
    public $disk_izin = "dokumen_izin";
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function index($type=null){
        return view('izin.index',['type'=>$type,'verifikasi'=>Session::get('levelActive')=="sdm"]);
    }
    public function verifikasi(){
        return view('izin.index',['type'=>null,'verifikasi'=>true]);
    }

    public function create(){
        return view('izin.create');
    }
    public function store(Request $request){
        try {
            $validator      = validator($request->all(), CreateIzinRuleReq::create(Session::get("nidn"), Session::get("nip")));

            if(count($validator->errors())){
                return redirect()->route('izin.create')->withInput()->withErrors($validator->errors()->toArray());    
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
                $fileSystem = new FileSystem(new OptionFileDefault($request->file("dokumen"),$this->disk_izin));
                $file = $fileSystem->storeFileWithReplaceFileAndReturnFileLocation();
            }

            $this->commandBus->dispatch(new CreateIzinCommand(
                Session::has("nidn")? Creator::buildDosen(DosenReferensi::make(Session::get("nidn"))):null,
                Session::has("nip")? Creator::buildPegawai(PegawaiReferensi::make(Session::get("nip"))):null,
                new Date($request->get("tanggal_pengajuan")),
                $request->get("tujuan"),
                Creator::buildJenisIzin(JenisIzinReferensi::make($request->get("jenis_izin"))),
                $file,
                $request->has("verifikasi")? Creator::buildPegawai(PegawaiReferensi::make($request->get("verifikasi"))):null,
                "menunggu",
            ));
            Session::flash(TypeNotif::Create->val(), "berhasil tambah data");

            return redirect()->route('izin.index2',['type'=>Session::get('levelActive')=="pegawai"? "tendik":"dosen"]);
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
            return redirect()->route('izin.index2',['type'=>Session::get('levelActive')=="pegawai"? "tendik":"dosen"]);
        }
    }
    public function update(Request $request){
        try {
            if(!$request->has('dokumen'))
                $request = request()->merge(["dokumen"=> "0"]);

            $validator      = validator($request->all(), UpdateIzinRuleReq::create(Session::get("nidn"), Session::get("nip")));

            if(count($validator->errors())){
                return redirect()->route('izin.edit',["id"=>$request->get("id")])->withInput()->withErrors($validator->errors()->toArray());    
            } 

            $izin = $this->queryBus->ask(new GetIzinQuery($request->get("id")));
            if($request->has("dokumen") && $request->file("dokumen")!=null){
                // $izin = $this->queryBus->ask(new GetIzinQuery($request->get("id")));
                if(Storage::disk($this->disk_izin)->exists($izin?->GetDokumen())){
                    Storage::disk($this->disk_izin)->delete($izin?->GetDokumen());
                }
                $fileSystem = new FileSystem(new OptionFileDefault($request->file("dokumen"),"dokumen_izin"));
                $file = $fileSystem->storeFileWithReplaceFileAndReturnFileLocation();
            } else{
                $file = $izin->GetDokumen();
            }

            $this->commandBus->dispatch(new UpdateIzinCommand(
                $request->get('id'), 
                Session::has("nidn")? Creator::buildDosen(DosenReferensi::make(Session::get("nidn"))):null,
                Session::has("nip")? Creator::buildPegawai(PegawaiReferensi::make(Session::get("nip"))):null,
                new Date($request->get("tanggal_pengajuan")),
                $request->get("tujuan"),
                Creator::buildJenisIzin(JenisIzinReferensi::make($request->get("jenis_izin"))),
                $file,
                $request->has("verifikasi")? Creator::buildPegawai(PegawaiReferensi::make($request->get("verifikasi"))):null,
                $izin->GetStatus(),
            ));
            Session::flash(TypeNotif::Update->val(), "berhasil ubah data");

            return redirect()->route('izin.index2',['type'=>Session::get('levelActive')=="pegawai"? "tendik":"dosen"]);
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

            return redirect()->route('izin.index2',['type'=>Session::get('levelActive')=="pegawai"? "tendik":"dosen"]);
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('izin.index2',['type'=>Session::get('levelActive')=="pegawai"? "tendik":"dosen"]);
        }
    }
    public function approval($id,$type){
        $izin = $this->queryBus->ask(new GetIzinQuery($id));
        $redirect = match(true){
            Session::get('levelActive')=="sdm" && !is_null($izin->GetDosen()) => redirect()->route('izin.index2',['type'=>'dosen']),
            Session::get('levelActive')=="sdm" && !is_null($izin->GetPegawai()) => redirect()->route('izin.index2',['type'=>'tendik']),
            in_array(Session::get('levelActive'), ["dosen","pegawai"]) => redirect()->route('izin.index2',['type'=>'verifikasi']),
            default=>redirect()->route('izin.index'),
        };

        try {
            // if(!in_array($type,["terima","tolak"])) throw new Exception("command invalid");

            $this->commandBus->dispatch(new ApprovalIzinCommand($id,$type,null));
            Session::flash(TypeNotif::Create->val(), "berhasil $type izin");

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
            $jenis_izin     = $request->has('jenis_izin')? $request->query('jenis_izin'):null;
            $status         = $request->has('status')? $request->query('status'):null;
            $tanggal_mulai  = $request->has('tanggal_mulai')? $request->query('tanggal_mulai'):null;
            $tanggal_akhir  = $request->has('tanggal_akhir')? $request->query('tanggal_akhir'):null;
            $type_export    = $request->has('type_export')? $request->query('type_export'):null;

            $file_name = "izin";
            $izin = Izin::with(['JenisIzin','Dosen','Pegawai']);

            if(is_null($type_export)){
                throw new Exception("belum pilih cetak sebagai apa");
            }

            if($type=="dosen" && !is_null($nama)){
                $izin->where('nidn',$nama);
                $file_name = $file_name."_$nama";
            } else if($type=="dosen" && is_null($nama)){
                $izin->whereNotNull('nidn');
                $file_name = $file_name."_semua-nama";
            } else if($type=="tendik" && !is_null($nama)){
                $izin->where('nip',$nama);
                $file_name = $file_name."_$nama";
            } else if($type=="tendik" && is_null($nama)){
                $izin->whereNotNull('nip');
                $file_name = $file_name."_semua-nama";
            }
            if($jenis_izin){
                $izin->where('id_jenis_izin',$jenis_izin);
                $file_name = $file_name."_$jenis_izin";
            }
            if($status){
                $izin->where('status',$status);
                $file_name = $file_name."_$status";
            }
            if($tanggal_mulai && is_null($tanggal_akhir)){
                $izin->where('tanggal_mulai',$tanggal_mulai);
                $file_name = $file_name."_$tanggal_mulai";
            }
            else if($tanggal_akhir && is_null($tanggal_mulai)){
                $izin->where('tanggal_akhir',$tanggal_akhir);
                $file_name = $file_name."_$tanggal_akhir";
            } else if($tanggal_mulai && $tanggal_akhir){
                $izin->whereBetween('tanggal_pengajuan', [$tanggal_mulai, $tanggal_akhir]);

                $file_name = $file_name."_$tanggal_mulai-$tanggal_akhir";
            }
            $list_izin = $izin->get();

            if($type_export=="pdf"){
                $file = PdfX::From(
                    "template.export_izin", 
                    [
                        "list_izin"=>$list_izin
                    ], 
                    FolderX::FromPath(public_path('export/pdf')), 
                    "$file_name.pdf"
                );
                return FileManager::StreamFile($file);
            } else{
                $list_izin = $list_izin->reduce(function($carry,$item){
                    $carry[] = [
                        'nama'=>match(true){
                            !is_null($item->Dosen) && is_null($item->Pegawai)=>$item->Dosen->nama_dosen,
                            is_null($item->Dosen) && !is_null($item->Pegawai)=>$item->Pegawai->nama,
                            default=>"NA"
                        },
                        'tanggal_izin' =>Carbon::parse($item->tanggal_pengajuan)->setTimezone('Asia/Jakarta')->format("d F Y"),
                        'jenis_izin' =>$item->JenisIzin?->nama,
                        'tujuan'=>$item->tujuan,
                        'catatan'=>$item->catatan,
                    ];

                    return $carry;
                });
                return Excel::download(new ExportIzinXls(collect($list_izin), ['nama','tanggal izin','Jenis Izin','Tujuan','Catatan']), "$file_name.xlsx");
            }
        } catch (Exception $e) {
            // throw $e;
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return empty($type)? redirect()->route('izin.index'):redirect()->route('izin.index2',['type'=>$type]);
        }
    }
}
