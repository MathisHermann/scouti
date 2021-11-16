<?php

namespace App\Http\Livewire;

use App\Models\SearchEngineResult;
use Livewire\Component;

class DisplayResults extends Component
{

    public $last_search_parameters;
    public $results;

    public function render()
    {
        $this->get_data();
        return view('livewire.display-results');
    }

    public function get_data()
    {
        $this->last_search_parameters = [];
        ray()->showQueries();
        $this->results = SearchEngineResult::fromLastRequests(3)->get();
        //$this->results = SearchEngineResult::lastRequests()->get();
        dd($this->results);
    }
}
