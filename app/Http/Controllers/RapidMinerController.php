<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class RapidMinerController extends Controller
{
    public static function getAccessToken()
    {
        $url = env('RAPID_MINER_ADDRESS') . '/api/rest/tokenservice';

        $response = Http::acceptJson()->withBasicAuth(env('RAPID_MINER_USER'), env('RAPID_MINER_PASSWORD'))->get($url)->json();

        return $response['idToken'];
    }

    public static function deployProcess($id_token, $job = 'test')
    {
        $url = env('RAPID_MINER_ADDRESS') . '/api/rest/process/' . $job;

        $headers = [
            'Authorization' => 'Bearer ' . $id_token,
            'Content-Type' => 'application/json'
        ];

        $body = '{\n  "process": "' . $job . '",\n  "location": "/home/bot/' . $job . '",\n  "reportError": true\n}';


        $response = Http::withBody(json_encode($body), 'application/json')->withHeaders($headers)->post($url);


        return $response;
    }
}
