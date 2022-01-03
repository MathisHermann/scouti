<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class RapidMinerController extends Controller
{
    public static function deployProcess($job = 'pp')
    {
        $url = env('RAPID_MINER_ADDRESS') . '/executions/schedules';
        $id_token = self::getAccessToken();

        $headers = [
            'Authorization' => 'Bearer ' . $id_token,
            'Content-Type' => 'application/json'
        ];

        //$body = '{\n  "process": "' . $job . '",\n  "location": "/home/bot/' . $job . '",\n  "reportError": true\n}';
        $body = json_decode('{  "job": {  "location": "/home/bot/'. $job . '",  "queueName": "DEFAULT"},  "force": true,  "reportError": true }');

        return Http::withBody(json_encode($body), 'application/json')->withHeaders($headers)->post($url);
    }

    // Return the status of the job

    public static function getJobStatus()
    {
        $id_token = self::getAccessToken();
        $url = env('RAPID_MINER_ADDRESS') . '/executions/jobs';

        $headers = [
            'Authorization' => 'Bearer ' . $id_token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];

        $response = Http::withHeaders($headers)->get($url);
        $response_json = json_decode($response);
        return $response_json->content[0]->state;
    }

    public static function getAccessToken()
    {
        $url = env('RAPID_MINER_ADDRESS') . '/api/rest/tokenservice';
        $response = Http::acceptJson()->withBasicAuth(env('RAPID_MINER_USER'), env('RAPID_MINER_PASSWORD'))->get($url)->json();
        return $response['idToken'];
    }
}
