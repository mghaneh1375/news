<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

include_once __DIR__ . '/Common.php';

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function makeValidInput($input) {
        $input = addslashes($input);
        $input = trim($input);
    //    if(get_magic_quotes_gpc())
    //        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }
    
}
