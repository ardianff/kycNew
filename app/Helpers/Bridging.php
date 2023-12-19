<?php

use GuzzleHttp\Client;

class Bridging
{
    public static function GetToken()
    {
        // $client = new Client();
        // self::$instance = new Bridging;
        // $params['headers'] = [
        //     'Content-Type' => 'application/x-www-form-urlencoded',
        // ];
        // $endpoint = 'accesstoken?grant_type=client_credentials';
        // $params['form_params'] = array(
        //     'client_id' => self::$instance->clientid,
        //     'client_secret' => self::$instance->clientsecret
        // );

        // $response = $client->post(self::$instance->oauth_url . $endpoint, $params);
        // $responseBody = json_decode($response->getBody());
        // $GetToken = $responseBody->access_token;
        // return $GetToken;
    }
    public static function GetTokenBaru($client_key, $secret_key)
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
}
