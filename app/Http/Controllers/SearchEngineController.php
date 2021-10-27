<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class SearchEngineController extends Controller
{
    public static function getSearchEngineLinks($query, $parameters = ['start' => 1])
    {
        return collect(Http::acceptJson()->timeout(5)->get(env('GOOGLE_URL') . '?cx=' . env('GOOGLE_SEARCH_ENGINE') .  '&q=' . $query .  '&key=' . env('GOOGLE_API_KEY') .  '&start=' . $parameters['start'])->json());
    }

    /**
     * Create a collection containing all links found by the search engine.
     * @param $list
     * @return \Illuminate\Support\Collection
     */
    public static function getFormattedURL($list)
    {
        $formatted_URLs = collect();
        foreach ($list['items'] as $list_item) {
            $formatted_URLs->add($list_item['link']);
        }
        return $formatted_URLs;
    }
}
