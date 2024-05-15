<?php

namespace Architecture\Shared\Facades;

use App\View\Components\NotifAlert;
use Architecture\Domain\Enum\TypeNotif;
use Illuminate\Support\Facades\Session;

trait AlertNotif{
    public static function show()
    {
        $message = false;
        $class = false;

        if(Session::has(TypeNotif::Error->val())){
            $message = Session::get(TypeNotif::Error->val());
            $class = 'alert alert-danger';
        } else if(Session::has(TypeNotif::Create->val())){
            $message = Session::get(TypeNotif::Create->val());
            $class = 'alert alert-success';
        } else if(Session::has(TypeNotif::Update->val())){
            $message = Session::get(TypeNotif::Update->val());
            $class = 'alert alert-warning';
        } else if (Session::has(TypeNotif::Delete->val())){
            $message = Session::get(TypeNotif::Delete->val());
            $class = 'alert alert-danger';
        }

        $x = new NotifAlert($message, $class);
        return $x->render();
    }
}