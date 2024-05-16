<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\JenisSPPD\Create\CreateJenisSPPDCommand;
use Architecture\Application\JenisSPPD\Delete\DeleteJenisSPPDCommand;
use Architecture\Application\JenisSPPD\FirstData\GetJenisSPPDQuery;
use Architecture\Application\JenisSPPD\Update\UpdateJenisSPPDCommand;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\Domain\RuleValidationRequest\JenisSPPD\CreateJenisSPPDRuleReq;
use Architecture\Domain\RuleValidationRequest\JenisSPPD\DeleteJenisSPPDRuleReq;
use Architecture\Domain\RuleValidationRequest\JenisSPPD\UpdateJenisSPPDRuleReq;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class JenisSPPDController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function Index(){
        return view('jenis_sppd.index');
    }

    public function create(){
        return view('jenis_sppd.create');
    }
    public function store(Request $request){
        try {
            $validator      = validator($request->all(), CreateJenisSPPDRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('jenis_sppd.create')->withInput()->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new CreateJenisSPPDCommand(
                $request->get("nama"),
            ));
            Session::flash(TypeNotif::Create->val(), "berhasil tambah data");

            return redirect()->route('jenis_sppd.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('jenis_sppd.create')->withInput();
        }
    }
    public function edit($id){
        try {
            $JenisSPPD = $this->queryBus->ask(new GetJenisSPPDQuery($id));
            
            return view('jenis_sppd.edit',[
                "JenisSPPD"=>$JenisSPPD
            ]);
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('jenis_sppd.index');
        }
    }
    public function update(Request $request){
        try {
            if(!$request->has('dokumen'))
                $request = request()->merge(["dokumen"=> "0"]);

            $validator      = validator($request->all(), UpdateJenisSPPDRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('jenis_sppd.edit',["id"=>$request->get("id")])->withInput()->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new UpdateJenisSPPDCommand(
                $request->get('id'), 
                $request->get("nama"),
            ));
            Session::flash(TypeNotif::Update->val(), "berhasil ubah data");

            return redirect()->route('jenis_sppd.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('jenis_sppd.edit',["id"=>$request->get('id')])->withInput();
        }
    }
    public function delete($id){
        $request = request()->merge(["id"=> $id]);
        try {
            $validator      = validator($request->all(), DeleteJenisSPPDRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('jenis_sppd.index')->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new DeleteJenisSPPDCommand($id));
            Session::flash(TypeNotif::Create->val(), "berhasil hapus data");

            return redirect()->route('jenis_sppd.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('jenis_sppd.index');
        }
    }
}
