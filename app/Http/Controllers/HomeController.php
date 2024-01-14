<?php

namespace App\Http\Controllers;

use Bridging;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Master;
use Modul;

class HomeController extends Controller
{
    public function auth()
    {
        // $key = json_decode((file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/satu-sehat/key')), true);
        // $key = json_decode((file_get_contents('http://119.2.50.170:6535/satu-sehat/key')), true);

        $client = new Client();
        // Ganti URL dengan URL yang sesuai untuk mendapatkan kunci
        $keyUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/satu-sehat/key';

        try {
            // Lakukan permintaan GET untuk mendapatkan kunci
            $response = $client->get($keyUrl);

            // Periksa apakah permintaan berhasil (kode status 200)
            if ($response->getStatusCode() == 200) {
                // Mendapatkan konten respons dan menguraikannya sebagai JSON
                $key = json_decode($response->getBody(), true);

                // Lakukan operasi apa pun dengan kunci yang didapatkan
                // var_dump($key);
                $pusk = json_decode(Master::SecurityDecode($key['data']), true);

                $token = Bridging::GetTokenBaru($pusk['client'], $pusk['secret']);
                return $token;
            } else {
                // Tangani jika permintaan tidak berhasil
                echo 'Gagal mendapatkan kunci. Kode status: ' . $response->getStatusCode();
            }
        } catch (Exception $e) {
            // Tangani kesalahan apapun yang mungkin terjadi selama permintaan
            echo 'Terjadi kesalahan: ' . $e->getMessage();
        }
    }
    public function index(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'nama' => 'required|string',
            'nik' => 'required|numeric|digits:16',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors();
            $response['status']   = false;
            $response['message']  = $error;
            return response()->json($response, 400);
        } else {
            $json = Modul::generateUrl($request->nama, $request->nik, $this->auth(), 'https://api-satusehat.kemkes.go.id/kyc/v1/generate-url', 'production');
            return view('kyc', ['url' => json_decode($json, true)]);
        }
    }
}
