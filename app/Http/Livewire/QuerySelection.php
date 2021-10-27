<?php

namespace App\Http\Livewire;

use App\Http\Controllers\SearchEngineController;
use Livewire\Component;

class QuerySelection extends Component
{

    public $terms;

    public function mount()
    {
        $this->terms = [['value' => ''], ['value' => '']];
    }

    public function render()
    {
        return view('livewire.query-selection');
    }

    /**
     * Gets called on firing the "Find results" button in the frontend.
     * Call the  function to make the search engine query string.
     * Call the search engine with the according string.
     */
    public function find_results()
    {
        $query = $this->make_string();
        $result = SearchEngineController::getSearchEngineLinks($query);
        dd($result);
    }

    /**
     * Concatenate the query string for the search engine.
     * Fields with multiple words are concatenated to a phrase.
     * Blanks are replaced with '%20'.
     * @return string
     */
    private function make_string(): string
    {
        $query = '';

        for ($i = 0; $i < sizeof($this->terms); $i++) {

            $is_phrase = strpos($this->terms[$i]['value'], ' ');

            if ($this->terms[$i]['value'] && strlen($this->terms[$i]['value']) > 0) {
                $part = str_replace(' ', '%20', $this->terms[$i]['value']);
                $query .= (strlen($query) > 0 ? '%20' : '') . ($is_phrase ? '"' : '') . $part . ($is_phrase ? '"' : '');
            }
        }

        return $query;

    }

}
