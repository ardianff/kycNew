<?php

use App\KeySatuSehat;
use App\Setting;
use App\SettingSatuSehat;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;

class Bridging
{

    private static $instance;
    private $oauth_url, $base_url, $consent_url, $kfa_url, $kfav2_url, $organization, $clientid, $clientsecret;

    public final function __construct()
    {
        $this->oauth_url = self::setting()['endpoint']['oauth_url'];
        $this->base_url = self::setting()['endpoint']['base_url'];
        $this->consent_url = self::setting()['endpoint']['consent_url'];
        $this->kfa_url = self::setting()['endpoint']['kfa_url'];
        $this->kfav2_url = self::setting()['endpoint']['kfav2_url'];
        $this->organization = self::setting()['organization'];
        $this->clientid = self::setting()['client_id'];
        $this->clientsecret = self::setting()['client_secret'];
    }

    public static function setting()
    {
        $user = User::where('role', '=', 'PUSKESMAS')->first();
        $endpoint = SettingSatuSehat::where('mode', '=', $user->mode)->first();
        $key = KeySatuSehat::where('mode', '=', $user->mode)->where('kode_puskesmas', '=', $user->kode_puskesmas)->first();

        $setting = array(
            'mode' => $user->mode,
            'endpoint' => $endpoint,
            'organization' => $key->organization_id,
            'client_id' => $key->client_key,
            'client_secret' => $key->secret_key,
        );
        return $setting;
    }


    public static function GetToken()
    {
        $client = new Client();
        self::$instance = new Bridging;
        $params['headers'] = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        $endpoint = 'accesstoken?grant_type=client_credentials';
        $params['form_params'] = array(
            'client_id' => self::$instance->clientid,
            'client_secret' => self::$instance->clientsecret
        );

        $response = $client->post(self::$instance->oauth_url . $endpoint, $params);
        $responseBody = json_decode($response->getBody());
        $GetToken = $responseBody->access_token;
        return $GetToken;
    }
    private static function GetTokenBaru($client_key, $secret_key)
    {
        $client = new Client();
        $request = $client->get('http://119.2.50.171/satu-sehat/token', [
            'query' => [
                'client_key' => $client_key,
                'secret_key' => $secret_key
            ]
        ]);
        $response = json_decode($request->getBody()->getContents(), true);
        $decrypt = json_decode(Master::SecurityDecode($response['data']), true);
        return $decrypt['access_token'];
    }
    public static function Token($client_key = null, $secret_key = null)
    {
        $cek = Setting::where('jenis', '=', 'token')->latest('created_at')->first();
        if ($cek == null) {
            Setting::create(
                array(
                    'jenis' => 'token',
                    // 'value' => Bridging::GetTokenBaru($client_key, $secret_key)
                    'value' => Bridging::GetToken()
                )
            );
        } else {
            $cekSelisih = Setting::where('jenis', '=', 'token')->latest('created_at')->first();
            $from = Carbon::createFromFormat('Y-m-d H:i:s', $cekSelisih->created_at);
            $to = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->format('Y-m-d H:i:s'));

            $diffInMinutes = $to->diffInMinutes($from);
            // cek token apakah ketika dibuat sdh lebih dari 2 jam jika iya hapus token lama & generate token baru
            // default 240 menit
            if ($diffInMinutes > 210) {
                $cekSelisih->delete();
                Setting::create(
                    array(
                        'jenis' => 'token',
                        'value' => Bridging::GetToken()
                    )
                );
            }
        }
        $ambil = Setting::where('jenis', '=', 'token')->latest('created_at')->first();

        return $ambil->value;
    }
    public static function kirimUrlKyc($payload, $token, $url)
    {
        $client = new Client();
        $options = [
            'headers' => [
                'X-Debug-Mode' => '0',
                'Content-Type' => 'text/plain',
                'Authorization' => 'Bearer ' . $token
            ],
            'body' => $payload
        ];

        $response = $client->post($url, $options);

        return $response->getBody()->getContents();
    }
}
