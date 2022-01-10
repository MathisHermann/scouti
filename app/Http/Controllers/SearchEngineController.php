<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class SearchEngineController extends Controller
{
    /**
     * Make requests to the search engine and extract the links used to proceed the process
     * @param $query
     * @return \Illuminate\Support\Collection
     */
    public static function getSearchEngineLinks($query)
    {
        // Google limits the queries per day. Thus, there is a limit for the search.
        $search_limit = 5;
        $limit = 0;
        $result = collect();

        $response = self::make_request($query);
        $limit += 1;

        $result->add($response);

        $more_results = true;
        while ($more_results && $limit < $search_limit) {
            try {
                $parameters['startIndex'] = $result[0]['queries']['nextPage'][0]['startIndex'];
                $response = self::make_request($query, $parameters);
                $limit += 1;
                $result->add($response);
            } catch (\Exception $e) {
                $more_results = false;
            }
        }

        return $result;
    }

    /**
     * Create a collection containing all links found by the search engine.
     * @param $list
     * @return \Illuminate\Support\Collection
     */
    public static function getFormattedURLs($result)
    {
        try {
            $formatted_URLs = collect();
            foreach ($result as $list) {
                foreach ($list['items'] as $list_item) {
                    $formatted_URLs->add($list_item['link']);
                }
            }
        } catch (\Exception $e) {
            // TODO: Make tracker to not exceed the search limit (error 429, reason 'rateLimitExceeded')
            dd($e, $result);
        }

        return $formatted_URLs;
    }

    /**
     * Make the request to the search engine
     * @param $query
     * @param int[] $parameters
     * @return array|mixed
     */
    private static function make_request($query, $parameters = ['startIndex' => 1])
    {
        return Http::acceptJson()->get(env('GOOGLE_URL') . '?cx=' . env('GOOGLE_SEARCH_ENGINE_1_ID') . '&q=' . $query . '&key=' . env('GOOGLE_API_KEY') . '&start=' . $parameters['startIndex'])->json();
    }
}
