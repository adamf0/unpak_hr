<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\SPPD\Create\CreateSPPDCommand;
use Architecture\Application\SPPD\Delete\DeleteSPPDCommand;
use Architecture\Application\SPPD\FirstData\GetSPPDQuery;
use Architecture\Application\SPPD\Update\UpdateSPPDCommand;
use Architecture\Domain\Creational\Creator;
use Architecture\Domain\Entity\JenisSPPDReferensi;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\Domain\RuleValidationRequest\SPPD\CreateSPPDRuleReq;
use Architecture\Domain\RuleValidationRequest\SPPD\DeleteSPPDRuleReq;
use Architecture\Domain\RuleValidationRequest\SPPD\UpdateSPPDRuleReq;
use Architecture\Domain\ValueObject\Date;
use Exception;
use Illuminate\Http\Request;
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
}
