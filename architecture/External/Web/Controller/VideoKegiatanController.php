<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\VideoKegiatan\Create\CreateVideoKegiatanCommand;
use Architecture\Application\VideoKegiatan\Delete\DeleteVideoKegiatanCommand;
use Architecture\Application\VideoKegiatan\FirstData\GetVideoKegiatanQuery;
use Architecture\Application\VideoKegiatan\Update\UpdateVideoKegiatanCommand;
use Architecture\Domain\Enum\TypeNotif;
use Architecture\Domain\RuleValidationRequest\Rule\CreateVideoKegiatanRuleReq;
use Architecture\Domain\RuleValidationRequest\Rule\DeleteVideoKegiatanRuleReq;
use Architecture\Domain\RuleValidationRequest\Rule\UpdateVideoKegiatanRuleReq;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VideoKegiatanController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function Index(){
        return view('videoKegiatan.index');
    }

    public function create(){
        return view('videoKegiatan.create');
    }
    public function store(Request $request){
        try {
            $validator      = validator($request->all(), CreateVideoKegiatanRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('videoKegiatan.create')->withInput()->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new CreateVideoKegiatanCommand(
                $request->get('nama'),
                $request->get('nilai'),
            ));
            Session::flash(TypeNotif::Create->val(), "berhasil tambah data ".$request->get('nama'));

            return redirect()->route('videoKegiatan.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('videoKegiatan.create')->withInput();
        }
    }
    public function edit($id){
        try {
            $VideoKegiatan = $this->queryBus->ask(new GetVideoKegiatanQuery($id));
            
            return view('videoKegiatan.edit',[
                "videoKegiatan"=>$VideoKegiatan
            ]);
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('videoKegiatan.index');
        }
    }
    public function update(Request $request){
        try {
            $validator      = validator($request->all(), UpdateVideoKegiatanRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('videoKegiatan.edit',["id"=>$request->get("id")])->withInput()->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new UpdateVideoKegiatanCommand(
                $request->get('id'), 
                $request->get('nama'),
                $request->get('nilai'),
            ));
            Session::flash(TypeNotif::Update->val(), "berhasil ubah data ".$request->get('nama'));

            return redirect()->route('videoKegiatan.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('videoKegiatan.edit',["id"=>$request->get('id')])->withInput();
        }
    }
    public function delete($id){
        $request = request()->merge(["id"=> $id]);
        try {
            $validator      = validator($request->all(), DeleteVideoKegiatanRuleReq::create());

            if(count($validator->errors())){
                return redirect()->route('videoKegiatan.index')->withErrors($validator->errors()->toArray());    
            } 
            
            $this->commandBus->dispatch(new DeleteVideoKegiatanCommand($id));
            Session::flash(TypeNotif::Create->val(), "berhasil hapus data");

            return redirect()->route('videoKegiatan.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('videoKegiatan.index');
        }
    }
}
