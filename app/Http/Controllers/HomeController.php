<?php

namespace App\Http\Controllers;

use Bridging;
use Illuminate\Http\Request;
use Master;
use Modul;

class HomeController extends Controller
{
    public function auth()
    {
        // $key = json_decode((file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/satu-sehat/key')), true);
        $key = json_decode((file_get_contents('http://119.2.50.170:6512/satu-sehat/key')), true);
        $pusk = json_decode(Master::SecurityDecode($key['data']), true);

        $token = Bridging::GetTokenBaru($pusk['client'], $pusk['secret']);
        return $token;
    }
    public function index()
    {
        $json = Modul::generateUrl('Ardian Ferdy Firmansyah', '3374102302990001', $this->auth(), 'https://api-satusehat.kemkes.go.id/kyc/v1/generate-url', 'production');
    }
}
