<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\JenisIzin\Create\CreateJenisIzinCommand;
use Architecture\Application\JenisIzin\Delete\DeleteJenisIzinCommand;
use Architecture\Application\JenisIzin\FirstData\GetJenisIzinQuery;
use Architecture\Application\JenisIzin\Update\UpdateJenisIzinCommand;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\Domain\RuleValidationRequest\JenisIzin\CreateJenisIzinRuleReq;
use Architecture\Domain\RuleValidationRequest\JenisIzin\DeleteJenisIzinRuleReq;
use Architecture\Domain\RuleValidationRequest\JenisIzin\UpdateJenisIzinRuleReq;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class JenisIzinController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function Index(){
        return view('jenis_izin.index');
    }

    public function create(){
        return view('jenis_izin.create');
    }
    public function store(Request $request){
        try {
            $validator      = validator($request->all(), CreateJenisIzinRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('jenis_izin.create')->withInput()->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new CreateJenisIzinCommand(
                $request->get("nama"),
            ));
            Session::flash(TypeNotif::Create->val(), "berhasil tambah data ".$request->get('nama'));

            return redirect()->route('jenis_izin.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('jenis_izin.create')->withInput();
        }
    }
    public function edit($id){
        try {
            $JenisIzin = $this->queryBus->ask(new GetJenisIzinQuery($id));
            
            return view('jenis_izin.edit',[
                "JenisIzin"=>$JenisIzin
            ]);
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('jenis_izin.index');
        }
    }
    public function update(Request $request){
        try {
            if(!$request->has('dokumen'))
                $request = request()->merge(["dokumen"=> "0"]);

            $validator      = validator($request->all(), UpdateJenisIzinRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('jenis_izin.edit',["id"=>$request->get("id")])->withInput()->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new UpdateJenisIzinCommand(
                $request->get('id'), 
                $request->get("nama"),
            ));
            Session::flash(TypeNotif::Update->val(), "berhasil ubah data ".$request->get('nama'));

            return redirect()->route('jenis_izin.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('jenis_izin.edit',["id"=>$request->get('id')])->withInput();
        }
    }
    public function delete($id){
        $request = request()->merge(["id"=> $id]);
        try {
            $validator      = validator($request->all(), DeleteJenisIzinRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('jenis_izin.index')->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new DeleteJenisIzinCommand($id));
            Session::flash(TypeNotif::Create->val(), "berhasil hapus data");

            return redirect()->route('jenis_izin.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('jenis_izin.index');
        }
    }
}
