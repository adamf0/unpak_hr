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
use Architecture\Domain\Entity\FolderX;
use Architecture\Domain\Entity\PegawaiReferensi;
use Architecture\Domain\Entity\PresensiReferensi;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\Domain\RuleValidationRequest\KlaimAbsen\CreateKlaimAbsenRuleReq;
use Architecture\Domain\RuleValidationRequest\KlaimAbsen\DeleteKlaimAbsenRuleReq;
use Architecture\Domain\RuleValidationRequest\KlaimAbsen\UpdateKlaimAbsenRuleReq;
use Architecture\External\Persistance\ORM\KlaimAbsen;
use Architecture\External\Port\ExportKlaimAbsenXls;
use Architecture\External\Port\FileSystem;
use Architecture\External\Port\PdfX;
use Architecture\Shared\Creational\FileManager;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class KlaimAbsenController extends Controller
{
    private $disk_klaim_absen = "dokumen_klaim_absen";
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function Index($type=null){
        return view('klaim_absen.index',['type'=>$type]);
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
                // $klaim_absen = $this->queryBus->ask(new GetKlaimAbsenQuery($request->get("id")));
                if(Storage::disk($this->disk_klaim_absen)->exists($klaim_absen?->GetDokumen())){
                    Storage::disk($this->disk_klaim_absen)->delete($klaim_absen?->GetDokumen());
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
        $klaim_absen = $this->queryBus->ask(new GetKlaimAbsenQuery($id));
        $redirect = match(true){
            Session::get('levelActive')=='sdm' && !is_null($klaim_absen->GetDosen())=>redirect()->route('klaim_absen.index2',['type'=>'dosen']),
            Session::get('levelActive')=='sdm' && !is_null($klaim_absen->GetPegawai())=>redirect()->route('klaim_absen.index2',['type'=>'tendik']),
            default=>redirect()->route('klaim_absen.index'),
        };

        try {
            $validator      = validator($request->all(), DeleteKlaimAbsenRuleReq::create());

            if(count($validator->errors())){
                return $redirect->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new DeleteKlaimAbsenCommand($id));
            Session::flash(TypeNotif::Create->val(), "berhasil hapus data");

            return $redirect;
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return $redirect;
        }
    }
    public function approval($id,$type){
        $klaim_absen = $this->queryBus->ask(new GetKlaimAbsenQuery($id));
        $redirect = match(true){
            !is_null($klaim_absen->GetDosen())=>redirect()->route('klaim_absen.index2',['type'=>'dosen']),
            !is_null($klaim_absen->GetPegawai())=>redirect()->route('klaim_absen.index2',['type'=>'tendik']),
            default=>redirect()->route('klaim_absen.index'),
        };
        try {
            if(!in_array($type,["terima","tolak"])) throw new Exception("command invalid");

            $this->commandBus->dispatch(new ApprovalKlaimAbsenCommand($id,$type));
            Session::flash(TypeNotif::Create->val(), "berhasil $type klaim presensi");

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
            $status         = $request->has('status')? $request->query('status'):null;
            $tanggal_mulai  = $request->has('tanggal_mulai')? $request->query('tanggal_mulai'):null;
            $tanggal_akhir  = $request->has('tanggal_akhir')? $request->query('tanggal_akhir'):null;
            $type_export    = $request->has('type_export')? $request->query('type_export'):null;

            $file_name = "klaim_absen";
            $klaim_absen = KlaimAbsen::with(['Presensi','Dosen','Pegawai']);

            if(is_null($type_export)){
                throw new Exception("belum pilih cetak sebagai apa");
            }

            if($type=="dosen" && !is_null($nama)){
                $klaim_absen->where('nidn',$nama);
                $file_name = $file_name."_$nama";
            } else if($type=="dosen" && is_null($nama)){
                $klaim_absen->whereNotNull('nidn');
                $file_name = $file_name."_semua-nama";
            } else if($type=="tendik" && !is_null($nama)){
                $klaim_absen->where('nip',$nama);
                $file_name = $file_name."_$nama";
            } else if($type=="tendik" && is_null($nama)){
                $klaim_absen->whereNotNull('nip');
                $file_name = $file_name."_semua-nama";
            }
            if($status){
                $klaim_absen->where('status',$status);
                $file_name = $file_name."_$status";
            }
            if($tanggal_mulai && is_null($tanggal_akhir)){
                $klaim_absen->where('tanggal_mulai',$tanggal_mulai);
                $file_name = $file_name."_$tanggal_mulai";
            }
            else if($tanggal_akhir && is_null($tanggal_mulai)){
                $klaim_absen->where('tanggal_akhir',$tanggal_akhir);
                $file_name = $file_name."_$tanggal_akhir";
            } else if($tanggal_mulai && $tanggal_akhir){
                $klaim_absen->whereBetween('tanggal_pengajuan', [$tanggal_mulai, $tanggal_akhir]);

                $file_name = $file_name."_$tanggal_mulai-$tanggal_akhir";
            }
            $list_klaim_absen = $klaim_absen->get();
            
            if($type_export=="pdf"){
                $file = PdfX::From(
                    "template.export_klaim_absen", 
                    [
                        "list_klaim_absen"=>$list_klaim_absen
                    ], 
                    FolderX::FromPath(public_path('export/pdf')), 
                    "$file_name.pdf"
                );
                return FileManager::StreamFile($file);
            } else{
                $list_klaim_absen = $list_klaim_absen->reduce(function($carry,$item){
                    $carry[] = [
                        'nama'=>match(true){
                            !is_null($item->Dosen) && is_null($item->Pegawai)=>$item->Dosen->nama_dosen,
                            is_null($item->Dosen) && !is_null($item->Pegawai)=>$item->Pegawai->nama,
                            default=>"NA"
                        },
                        'tanggal'=>empty($item->Presensi?->tanggal)? "":date('d F Y', strtotime($item->Presensi?->tanggal)),
                        'jam_masuk' =>(empty($item->jam_masuk)? "":date('H:i:s',strtotime($item->jam_masuk))),
                        'jam_keluar' =>(empty($item->jam_keluar)? "":date('H:i:s',strtotime($item->jam_keluar))),
                        'tujuan' =>$item->tujuan,
                    ];

                    return $carry;
                });
                return Excel::download(new ExportKlaimAbsenXls(collect($list_klaim_absen), ['nama','tanggal','jam masuk','jam keluar','tujuan']), "$file_name.xlsx");
            }
        } catch (Exception $e) {
            throw $e;
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return empty($type)? redirect()->route('klaim_absen.index'):redirect()->route('klaim_absen.index2',['type'=>$type]);
        }
    }
}
