<?php

namespace App\Http\Livewire;

use App\Http\Controllers\SearchEngineController;
use Livewire\Component;

class QuerySelection extends Component
{
    public $query;

    public function mount()
    {
        $this->query = [['value' => ''], ['value' => '']];
    }

    public function render()
    {
        return view('livewire.query-selection');
    }

    public function find_results()
    {
        $query_string = '';
        foreach ($this->query as $query_item) {
            if ($query_item['value'] && strlen($query_item['value']) > 0)
                $query_string = $query_string . '%20' . $query_item['value'];
        }

        // $result = SearchEngineController::getSearchEngine($query_string);
         dd($query_string);
        //dd($this->query);
    }

}
