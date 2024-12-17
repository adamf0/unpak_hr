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
        if(!$request->has("g-recaptcha-response")){
		    throw new Exception("Please complete the reCAPTCHA to proceed");
	    } else {
		    $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
		    $data = [
    			'secret' => "6Ldt5p0qAAAAAA9BtuoS2HgE_09EBlYL9NVzJN_Q",
    			'response' => $request->get("g-recaptcha-response"),
    			'remoteip' => $_SERVER['REMOTE_ADDR'],
		    ];

            // Initialize cURL session
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $verifyUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute the request
            $responseData = curl_exec($ch);
            curl_close($ch);

            // Decode the response from Google
            $responseKeys = json_decode($responseData, true);

            // Check if the verification was successful
            if (intval($responseKeys["success"]) !== 1) {
                // reCAPTCHA failed, handle accordingly
                throw new Exception("reCAPTCHA verification failed, please try again.");
            } 
	    }

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
