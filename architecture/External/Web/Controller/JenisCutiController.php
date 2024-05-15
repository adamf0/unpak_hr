<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\JenisCuti\Create\CreateJenisCutiCommand;
use Architecture\Application\JenisCuti\Delete\DeleteJenisCutiCommand;
use Architecture\Application\JenisCuti\FirstData\GetJenisCutiQuery;
use Architecture\Application\JenisCuti\Update\UpdateJenisCutiCommand;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\Domain\RuleValidationRequest\JenisCuti\CreateJenisCutiRuleReq;
use Architecture\Domain\RuleValidationRequest\JenisCuti\DeleteJenisCutiRuleReq;
use Architecture\Domain\RuleValidationRequest\JenisCuti\UpdateJenisCutiRuleReq;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class JenisCutiController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function Index(){
        return view('jenis_cuti.index');
    }

    public function create(){
        return view('jenis_cuti.create');
    }
    public function store(Request $request){
        try {
            if(!$request->has('dokumen'))
                $request = request()->merge(["dokumen"=> "0"]);
            
            $validator      = validator($request->all(), CreateJenisCutiRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('jenis_cuti.create')->withInput()->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new CreateJenisCutiCommand(
                $request->get("nama"),
                $request->get("min"),
                $request->get("max"),
                $request->get("dokumen"),
                $request->get("kondisi")==""? "{}":$request->get("kondisi"),
            ));
            Session::flash(TypeNotif::Create->val(), "berhasil tambah data ".$request->get('nama'));

            return redirect()->route('jenis_cuti.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('jenis_cuti.create')->withInput();
        }
    }
    public function edit($id){
        try {
            $JenisCuti = $this->queryBus->ask(new GetJenisCutiQuery($id));
            
            return view('jenis_cuti.edit',[
                "JenisCuti"=>$JenisCuti
            ]);
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('jenis_cuti.index');
        }
    }
    public function update(Request $request){
        try {
            if(!$request->has('dokumen'))
                $request = request()->merge(["dokumen"=> "0"]);

            $validator      = validator($request->all(), UpdateJenisCutiRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('jenis_cuti.edit',["id"=>$request->get("id")])->withInput()->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new UpdateJenisCutiCommand(
                $request->get('id'), 
                $request->get("nama"),
                $request->get("min"),
                $request->get("max"),
                $request->get("dokumen"),
                $request->get("kondisi")==""? "{}":$request->get("kondisi"),
            ));
            Session::flash(TypeNotif::Update->val(), "berhasil ubah data ".$request->get('nama'));

            return redirect()->route('jenis_cuti.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('jenis_cuti.edit',["id"=>$request->get('id')])->withInput();
        }
    }
    public function delete($id){
        $request = request()->merge(["id"=> $id]);
        try {
            $validator      = validator($request->all(), DeleteJenisCutiRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('jenis_cuti.index')->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new DeleteJenisCutiCommand($id));
            Session::flash(TypeNotif::Create->val(), "berhasil hapus data");

            return redirect()->route('jenis_cuti.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('jenis_cuti.index');
        }
    }
}
