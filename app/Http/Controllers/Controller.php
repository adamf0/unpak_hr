<?php

namespace App\Http\Controllers;
include  "../../public/security/config.php"; 
include  "../../public/security/project-security.php";

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
