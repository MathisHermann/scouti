<?php

namespace App\Http\Livewire;

use App\Http\Controllers\RapidMinerController;
use App\Models\RapidMinerResult;
use App\Models\SearchEngineRequest;
use Livewire\Component;

class DisplayResults extends Component
{

    public $last_search_parameters;
    public $results;
    public $number_of_results;
    public $current_request_number;

    public function render()
    {
        $process_finished = true;
      /*  while(!$process_finished) {
            $status = RapidMinerController::getJobStatus();
            if($status == 'FINISHED')
                $process_finished = true;
        }*/
        $this->get_data();
        return view('livewire.display-results');
    }

    public function get_data()
    {
        $this->number_of_results = 5;
        $current_request = SearchEngineRequest::all()->last();
        $this->current_request_number = 0;
        if ($current_request != null) {
            $this->current_request_number = $current_request->id;
            $this->last_search_parameters = json_decode($current_request->keywords, true);
        }


        $results = RapidMinerResult::where('search_engine_requests_id', $this->current_request_number)->orderBy('Score', 'desc')->orderBy('confidence', 'desc')->get();


        $scores = collect();
        foreach($results->groupBy('Keyword') as $kw_group) {
            $avg_score = $kw_group->pluck('Score')->avg();
            $avg_confidence = $kw_group->pluck('confidence')->avg();
            $final_score = round((($avg_score + 1) / 2 * $avg_confidence / 20) / 5, 1) * 5;
            $scores->add([
                'score' =>  $final_score,
                'keyword' => $kw_group->pluck('Keyword')->first(),
                'text' =>  $kw_group->sortByDesc('Score')->pluck('Text')->first()
            ]);
        }
        $scores->sortByDesc('score');
        $this->results = $scores;
    }
}
