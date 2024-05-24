<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\MasterKalendar\Create\CreateMasterKalendarCommand;
use Architecture\Application\MasterKalendar\Delete\DeleteMasterKalendarCommand;
use Architecture\Application\MasterKalendar\FirstData\GetMasterKalendarQuery;
use Architecture\Application\MasterKalendar\Update\UpdateMasterKalendarCommand;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\Domain\RuleValidationRequest\MasterKalendar\CreateMasterKalendarRuleReq;
use Architecture\Domain\RuleValidationRequest\MasterKalendar\DeleteMasterKalendarRuleReq;
use Architecture\Domain\RuleValidationRequest\MasterKalendar\UpdateMasterKalendarRuleReq;
use Architecture\Domain\ValueObject\Date;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MasterKalendarController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function Index(){
        return view('master_kalendar.index');
    }

    public function create(){
        return view('master_kalendar.create');
    }
    public function store(Request $request){
        try {
            $validator      = validator($request->all(), CreateMasterKalendarRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('master_kalendar.create')->withInput()->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new CreateMasterKalendarCommand(
                new Date($request->get("tanggal_mulai")),
                new Date($request->get("tanggal_berakhir")),
                $request->get("keterangan")
            ));
            Session::flash(TypeNotif::Create->val(), "berhasil tambah data");

            return redirect()->route('master_kalendar.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('master_kalendar.create')->withInput();
        }
    }
    public function edit($id){
        try {
            $MasterKalendar = $this->queryBus->ask(new GetMasterKalendarQuery($id));
            
            return view('master_kalendar.edit',[
                "MasterKalendar"=>$MasterKalendar
            ]);
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('master_kalendar.index');
        }
    }
    public function update(Request $request){
        try {
            if(!$request->has('dokumen'))
                $request = request()->merge(["dokumen"=> "0"]);

            $validator      = validator($request->all(), UpdateMasterKalendarRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('master_kalendar.edit',["id"=>$request->get("id")])->withInput()->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new UpdateMasterKalendarCommand(
                $request->get('id'), 
                new Date($request->get("tanggal_mulai")),
                new Date($request->get("tanggal_berakhir")),
                $request->get("keterangan")
            ));
            Session::flash(TypeNotif::Update->val(), "berhasil ubah data");

            return redirect()->route('master_kalendar.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('master_kalendar.edit',["id"=>$request->get('id')])->withInput();
        }
    }
    public function delete($id){
        $request = request()->merge(["id"=> $id]);
        try {
            $validator      = validator($request->all(), DeleteMasterKalendarRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('master_kalendar.index')->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new DeleteMasterKalendarCommand($id));
            Session::flash(TypeNotif::Create->val(), "berhasil hapus data");

            return redirect()->route('master_kalendar.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('master_kalendar.index');
        }
    }
}
