<?php

namespace App\Http\Controllers;

use Bridging;
use Illuminate\Http\Request;
use Master;

class HomeController extends Controller
{
    public function index()
    {
        $key = json_decode((file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/satu-sehat/key')), true);
        $pusk = json_decode(Master::SecurityDecode($key['data']), true);

        $token = Bridging::GetTokenBaru($pusk['client'], $pusk['secret']);
        return $token;
    }
}
