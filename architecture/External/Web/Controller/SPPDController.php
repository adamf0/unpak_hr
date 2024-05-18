<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\SPPD\Create\CreateSPPDCommand;
use Architecture\Application\SPPD\Delete\DeleteSPPDCommand;
use Architecture\Application\SPPD\FirstData\GetSPPDQuery;
use Architecture\Application\SPPD\Update\RejectSPPDCommand;
use Architecture\Application\SPPD\Update\UpdateSPPDCommand;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\FolderX;
use Architecture\Domain\Entity\JenisSPPDReferensi;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\Domain\RuleValidationRequest\SPPD\CreateSPPDRuleReq;
use Architecture\Domain\RuleValidationRequest\SPPD\DeleteSPPDRuleReq;
use Architecture\Domain\RuleValidationRequest\SPPD\UpdateSPPDRuleReq;
use Architecture\Domain\ValueObject\Date;
use Architecture\External\Port\PdfX;
use Architecture\Shared\Creational\FileManager;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SPPDController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function Index(){
        return view('sppd.index');
    }

    public function create(){
        return view('sppd.create');
    }
    public function store(Request $request){
        try {
            $validator      = validator($request->all(), CreateSPPDRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('sppd.create')->withInput()->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new CreateSPPDCommand(
                Session::get('nidn'),
                Session::get('nip'),
                Creator::buildJenisSPPD(JenisSppdReferensi::make(
                    $request->get('jenis_sppd')
                )),
                new Date($request->get('tanggal_berangkat')),
                new Date($request->get('tanggal_kembali')),
                $request->get('tujuan'),
                $request->get('keterangan'),
                "menunggu"
            ));
            Session::flash(TypeNotif::Create->val(), "berhasil tambah data");

            return redirect()->route('sppd.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('sppd.create')->withInput();
        }
    }
    public function edit($id){
        try {
            $SPPD = $this->queryBus->ask(new GetSPPDQuery($id));
            
            return view('sppd.edit',[
                "SPPD"=>$SPPD
            ]);
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('sppd.index');
        }
    }
    public function update(Request $request){
        try {
            $validator      = validator($request->all(), UpdateSPPDRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('sppd.edit',["id"=>$request->get("id")])->withInput()->withErrors($validator->errors()->toArray());    
            } 

            $sppd = $this->queryBus->ask(new GetSPPDQuery($request->get("id")));            
            $this->commandBus->dispatch(new UpdateSPPDCommand(
                $request->get('id'), 
                Session::get('nidn'),
                Session::get('nip'),
                Creator::buildJenisSPPD(JenisSPPDReferensi::make(
                    $request->get('jenis_sppd')
                )),
                new Date($request->get('tanggal_berangkat')),
                new Date($request->get('tanggal_kembali')),
                $request->get('tujuan'),
                $request->get('keterangan'),
                $sppd->GetStatus()
            ));
            Session::flash(TypeNotif::Update->val(), "berhasil ubah data");

            return redirect()->route('sppd.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('sppd.edit',["id"=>$request->get('id')])->withInput();
        }
    }
    public function delete($id){
        $request = request()->merge(["id"=> $id]);
        try {
            $validator      = validator($request->all(), DeleteSPPDRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('sppd.index')->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new DeleteSPPDCommand($id));
            Session::flash(TypeNotif::Create->val(), "berhasil hapus data");

            return redirect()->route('sppd.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('sppd.index');
        }
    }

    public function approval($id){
        try {
            throw new Exception("not impelementation");
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('sppd.index');
        }
    }

    public function reject($id){
        try {
            $this->commandBus->dispatch(new RejectSPPDCommand($id));
            Session::flash(TypeNotif::Create->val(), "berhasil tolak SPPD");

            return redirect()->route('sppd.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('sppd.index');
        }
    }

    public function export(Request $request){
        try {
            $nidn           = $request->has('nidn')? $request->query('nidn'):null;
            $nip            = $request->has('nip')? $request->query('nip'):null;
            $jenis_sppd     = $request->has('jenis_sppd')? $request->query('jenis_sppd'):null;
            $status         = $request->has('status')? $request->query('status'):null;
            $tanggal_berangkat  = $request->has('tanggal_berangkat')? $request->query('tanggal_berangkat'):null;
            $tanggal_kembali  = $request->has('tanggal_kembali')? $request->query('tanggal_kembali'):null;
            $type_export    = $request->has('type_export')? $request->query('type_export'):null;

            $file_name = "sppd";
            $sppd = DB::table('sppd');
            if($nidn){
                $sppd->where('nidn',$nidn);
                $file_name = $file_name."_$nidn";
            }
            if($nip){
                $sppd->where('nip',$nip);
                $file_name = $file_name."_$nip";
            }
            if($jenis_sppd){
                $sppd->where('id_jenis_sppd',$jenis_sppd);
                $file_name = $file_name."_$jenis_sppd";
            }
            if($status){
                $sppd->where('status',$status);
                $file_name = $file_name."_$status";
            }
            if($tanggal_berangkat && is_null($tanggal_kembali)){
                $sppd->where('tanggal_berangkat',$tanggal_berangkat);
                $file_name = $file_name."_$tanggal_berangkat";
            }
            else if($tanggal_kembali && is_null($tanggal_berangkat)){
                $sppd->where('tanggal_kembali',$tanggal_kembali);
                $file_name = $file_name."_$tanggal_kembali";
            } else if($tanggal_berangkat && $tanggal_kembali){
                $sppd->whereBetween('tanggal_berangkat', [$tanggal_berangkat, $tanggal_kembali])
                    ->whereBetween('tanggal_kembali', [$tanggal_berangkat, $tanggal_kembali]);

                $file_name = $file_name."_$tanggal_berangkat-$tanggal_kembali";
            }
            $list_sppd = $sppd->get();

            if($type_export=="pdf"){
                $file = PdfX::From(
                    "template.export_sppd", 
                    [
                        "list_sppd"=>$list_sppd
                    ], 
                    FolderX::FromPath(public_path('export/pdf')), 
                    "$file_name.pdf"
                );
            } else{
                throw new Exception("export type '$type_export' not implementation");
            }
    
            return FileManager::StreamFile($file);

        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('sppd.index');
        }
    }
}
