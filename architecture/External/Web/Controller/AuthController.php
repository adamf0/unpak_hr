<?php

namespace Architecture\External\Web\Controller;

use App\Http\Controllers\Controller;
use Architecture\Application\Abstractions\Messaging\ICommandBus;
use Architecture\Application\Abstractions\Messaging\IQueryBus;
use Architecture\Application\Auth\Authentication\AuthenticationCommand;
use Architecture\Application\Auth\LogOut\LogOutCommand;
use Architecture\Domain\Enum\TypeNotif;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function __construct(
        protected ICommandBus $commandBus,
        protected IQueryBus $queryBus
    ) {}
    
    public function Authorization(){
        return view('authorization');
    }

    public function Authentication(Request $request){
        try {
            // $validator      = validator($request->all(), LoginRuleReq::login());

            // if(count($validator->errors())){
            //     return redirect()->route('auth.authorization')->withInput()->withErrors($validator->errors()->toArray());
            // }

            $this->commandBus->dispatch(
                new AuthenticationCommand(
                    $request->username??null,
                    $request->password??null,
                )
            );
            return redirect()->route('dashboard.index');
        } catch (Exception $e) {
            Session::flash(TypeNotif::Error->val(), $e->getMessage());
            return redirect()->route('auth.authorization')->withInput()->withErrors(['alert-danger' => $e->getMessage()]);
        }
    }

    public function Logout(){
        $this->commandBus->dispatch(
            new LogOutCommand()
        );

        return redirect()->route('auth.authorization');
    }
}
