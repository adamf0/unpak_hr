<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Pengguna\Create\CreatePenggunaCommand;
use Architecture\Application\Pengguna\Delete\DeletePenggunaCommand;
use Architecture\Application\Pengguna\FirstData\GetPenggunaQuery;
use Architecture\Application\Pengguna\Update\UpdatePenggunaCommand;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\Domain\Enum\TypeRole;
use Architecture\Domain\RuleValidationRequest\Pengguna\CreatePenggunaRuleReq;
use Architecture\Domain\RuleValidationRequest\Pengguna\DeletePenggunaRuleReq;
use Architecture\Domain\RuleValidationRequest\Pengguna\UpdatePenggunaRuleReq;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PenggunaController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function Index(){
        return view('pengguna.index');
    }

    public function create(){
        return view('pengguna.create');
    }
    public function store(Request $request){
        try {
            $validator      = validator($request->all(), CreatePenggunaRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('pengguna.create')->withInput()->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new CreatePenggunaCommand(
                $request->get("username"),
                $request->get("password"),
                $request->get("nama"),
                TypeRole::parse($request->get("level")),
            ));
            Session::flash(TypeNotif::Create->val(), "berhasil tambah data");

            return redirect()->route('pengguna.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('pengguna.create')->withInput();
        }
    }
    public function edit($id){
        try {
            $Pengguna = $this->queryBus->ask(new GetPenggunaQuery($id));
            
            return view('pengguna.edit',[
                "Pengguna"=>$Pengguna
            ]);
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('pengguna.index');
        }
    }
    public function update(Request $request){
        try {
            if(!$request->has('dokumen'))
                $request = request()->merge(["dokumen"=> "0"]);

            $validator      = validator($request->all(), UpdatePenggunaRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('pengguna.edit',["id"=>$request->get("id")])->withInput()->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new UpdatePenggunaCommand(
                $request->get('id'), 
                $request->get("username"),
                $request->get("password"),
                $request->get("nama"),
                TypeRole::parse($request->get("level")),
            ));
            Session::flash(TypeNotif::Update->val(), "berhasil ubah data");

            return redirect()->route('pengguna.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('pengguna.edit',["id"=>$request->get('id')])->withInput();
        }
    }
    public function delete($id){
        $request = request()->merge(["id"=> $id]);
        try {
            $validator      = validator($request->all(), DeletePenggunaRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('pengguna.index')->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new DeletePenggunaCommand($id));
            Session::flash(TypeNotif::Create->val(), "berhasil hapus data");

            return redirect()->route('pengguna.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('pengguna.index');
        }
    }
}
