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
        $request = $client->get('http://192.168.80.249/satu-sehat/token', [
            'query' => [
                'client_key' => $client_key,
                'secret_key' => $secret_key
            ]
        ]);
        $response = json_decode($request->getBody()->getContents(), true);
        $decrypt = json_decode(Master::SecurityDecode($response['data']), true);
        return $decrypt['access_token'];
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
