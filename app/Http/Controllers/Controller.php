<?php

namespace App\Http\Controllers;
require_once __DIR__."/../../public/security/config.php"; 
require_once __DIR__."/../../public/security/project-security.php";

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
