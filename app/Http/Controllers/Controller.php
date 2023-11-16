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

    public static $DEFAULT_SITE_ID = 4;
    public static $LANG_MODE = 'both'; // 1- both, 2- just_fa 3- just_en

    public static function makeValidInput($input) {
        $input = addslashes($input);
        $input = trim($input);
    //    if(get_magic_quotes_gpc())
    //        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }
    public static function sendDeleteFileApiToServer($files, $server){

        if(config('app.env') === 'local')
            return ['status' => 'ok', 'result' => 'not in local'];

        $nonce = config('app.DeleteNonceCode');
        $apiUrl = "https://sr{$server}.koochita.com/api/deleteFileWithDir";

        $time = Carbon::now()->getTimestamp();
        $hash = Hash::make($nonce.'_'.$time);
        $files =  json_encode($files);

        $client = new \GuzzleHttp\Client();
        $response = $client->request('DELETE', $apiUrl, ['form_params' => [
            'code' => $hash,
            'time' => $time,
            'filesDirectory' => $files,
        ]]);
        $statusCode = $response->getStatusCode();
        $content = json_decode($response->getBody()->getContents());

        $status = ($statusCode == 200 && $content->status == 'ok') ? 'ok' : 'error';

        return ['status' => $status, 'result' => $content->result];
    }
    
}
