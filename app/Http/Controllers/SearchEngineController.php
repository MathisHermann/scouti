<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class SearchEngineController extends Controller
{
    public static function getSearchEngine($terms)
    {
        return Http::acceptJson()->timeout(5)->get(env('GOOGLE_URL') . '?cx=' . env('GOOGLE_SEARCH_ENGINE') .  '&q=' . $terms .  '&key=' . env('GOOGLE_API_KEY'))->json();
    }
}
