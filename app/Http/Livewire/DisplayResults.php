<?php

namespace App\Http\Livewire;

use App\Http\Controllers\RapidMinerController;
use App\Models\RapidMinerResult;
use App\Models\SearchEngineRequest;
use App\Models\User;
use Livewire\Component;

class DisplayResults extends Component
{

    public $last_search_parameters;
    public $results;
    public $current_request_number;
    public $users;
    public $user;
    public $default_user = 'Select User';
    public $results_id;
    public $request_number;
    public $process_successful;
    public $results_loaded;

    public function render()
    {
        $this->load_process_status();
        return view('livewire.display-results');
    }

    public function mount()
    {
        $this->get_data();
        $this->results_loaded = false;
        $this->request_number = 'Select';
        $this->results_id = null;
        $this->user = $this->default_user;
        $this->users = User::all();
    }

    public function get_data()
    {
        $current_request = SearchEngineRequest::all()->last();
        $this->current_request_number = 0;
        if ($current_request != null) {
            $this->current_request_number = $current_request->id;
            $this->last_search_parameters = json_decode($current_request->keywords, true);
        }

        $results = RapidMinerResult::where('search_engine_requests_id', $this->current_request_number)->orderBy('Score', 'desc')->orderBy('confidence', 'desc')->get();

        $this->calculate_score($results);
    }

    public function updatedUser()
    {
        if ($this->user != $this->default_user) {
            if ($this->user == '-')
                $results = RapidMinerResult::all();
            else {
                $user = User::where('name', $this->user)->first();
                $user_id = $user->id;
                $results = RapidMinerResult::where('users_id', $user_id)->get();
            }
            $this->results_id = $results->pluck('search_engine_requests_id')->unique();
            $this->request_number = 'Select';
        }
    }

    public function updatedRequestNumber()
    {
        $results = RapidMinerResult::where('search_engine_requests_id', $this->request_number)->orderBy('Score', 'desc')->orderBy('confidence', 'desc')->get();

        $current_request = SearchEngineRequest::find($this->request_number);
        $this->current_request_number = 0;
        if ($current_request != null) {
            $this->current_request_number = $current_request->id;
            $this->last_search_parameters = json_decode($current_request->keywords, true);
        }

        $this->calculate_score($results);
    }

    public function calculate_score($results)
    {
        $scores = collect();
        foreach ($results->groupBy('Keyword') as $kw_group) {
            $avg_score = $kw_group->pluck('Score')->avg();
            $avg_confidence = $kw_group->pluck('confidence')->avg();
            $final_score = round((($avg_score + 1) / 2 * $avg_confidence / 20) / 5, 1) * 5;
            $scores->add([
                'score' => $final_score,
                'keyword' => $kw_group->pluck('Keyword')->first(),
                'text' => $kw_group->sortByDesc('Score')->pluck('Text')->first()
            ]);
        }
        $scores->sortByDesc('score');
        $this->results = $scores;
    }

    public function load_process_status()
    {
        if (!$this->results_loaded) {
            $status = RapidMinerController::getJobStatus();
            if ($status == 'FINISHED' || $this->request_number == $this->current_request_number) {
                $this->process_successful = true;
                $this->results_loaded = true;
                $this->get_data();
            } elseif
            ($status == 'ERROR')
                $this->process_successful = false;
            else
                $this->process_successful = null;
        }
    }

}
