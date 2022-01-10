<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Http;

class RapidMinerController extends Controller
{
    /**
     * Schedule the RM process on the server. Process runs immediately after calling this function.
     * @param string $job
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
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

    /**
     * Get the status of the last scheduled job. Does not consider past jobs.
     * @return mixed
     */
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
        try {

        return $response_json->content[0]->state;
        } catch (Exception $exception) {
            return 'FINISHED';
        }
    }

    /**
     * Get the latest access token.
     * Is needed for each AI Hub api call and expires after some time.
     * Thus, it is newly called everytime the api is accessed.
     * @return mixed
     */
    public static function getAccessToken()
    {
        $url = env('RAPID_MINER_ADDRESS') . '/api/rest/tokenservice';
        $response = Http::acceptJson()->withBasicAuth(env('RAPID_MINER_USER'), env('RAPID_MINER_PASSWORD'))->get($url)->json();
        return $response['idToken'];
    }
}
