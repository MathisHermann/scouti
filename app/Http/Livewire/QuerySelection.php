<?php

namespace App\Http\Livewire;

use App\Http\Controllers\RapidMinerController;
use App\Http\Controllers\SearchEngineController;
use App\Models\IndustrySelection;
use App\Models\SearchEngineRequest;
use App\Models\SearchEngineResult;
use App\Models\User;
use Livewire\Component;
use Session;

class QuerySelection extends Component
{

    public $terms;
    public $industries;
    public $industry;
    public $default_industry = 'Select';
    public $users;
    public $user;
    public $default_user = '-';
    public $modal_msg;
    public $ignore = false;
    public $enable_loading;
    public $results_loaded;
    public $process_finished;

    public function mount()
    {
        $this->enable_loading = false;
        // TODO: remove the values if not needed anymore!
        $this->terms = [['value' => '']];
        $temp_industries = IndustrySelection::latest()->first();

        if ($temp_industries != null)
            $this->industries = json_decode($temp_industries->industries, true);
        else
            $this->industries = [];
        $this->industry = $this->default_industry;

        $this->users = User::all();
        $this->user = $this->default_user;

        $this->modal_msg = '';
    }


    public function render()
    {
        $this->load_process_status();
        return view('livewire.query-selection');
    }

    /**
     * Gets called on firing the "Find results" button in the frontend.
     * Call the  function to make the search engine query string.
     * Call the search engine with the according string.
     */
    public function find_results()
    {
        if ($this->industry == $this->default_industry) {
            Session::flash('error_dropdown_selection', 'Please select an industry option.');
            return redirect('/');
        } else {
            $query = $this->make_string();

            $user_id = null;
            if($this->user != $this->default_user)
            {
                $selected_user = User::where('name', $this->user)->first();
                $user_id = $selected_user->id;
            }

            $this->enable_loading = true;

            // TODO: Catch errors and make information accordingly
            $result = SearchEngineController::getSearchEngineLinks($query);
            $this->modal_msg = 'Processing Links';
            $result = SearchEngineController::getFormattedURLs($result);
            $this->enable_loading = false;


            // TODO: Check if date is not older than some specific days. Either in Query above or here in the if statement.
            $keywords_sorted = $this->terms;
            asort($keywords_sorted);

            $known_keywords = [];
            if (!$this->ignore && false)
                $known_keywords = SearchEngineRequest::knownKeywords($keywords_sorted)->get();

            if (sizeof($known_keywords) > 0) {
                dd('Fix this.');
            } else {
                if (!$this->ignore) {
                    // Add the industry to the selection of the search terms
                    $terms = $this->terms;
                    array_push($terms, ['industry' => $this->industry]);
                    $request = new SearchEngineRequest(
                        [
                            'users_id' => $user_id,
                            'keywords' => json_encode($terms),
                            'keywords_sorted' => json_encode($keywords_sorted),
                            'industry' => $this->industry,
                            'successful' => sizeof($result) > 0
                        ]
                    );
                    $request->save();

                    foreach ($result as $item) {
                        $se_result = new SearchEngineResult([
                            'url' => $item,
                            'search_engine_requests_id' => $request->id
                        ]);
                        $se_result->save();
                    }
                }

                $rapid_miner_response = RapidMinerController::deployProcess();
                $rapid_miner_response_ok = $rapid_miner_response->ok();
                if ($rapid_miner_response_ok)
                    return redirect('results');
                else {
                    Session::flash('error_dropdown_selection', 'Deployment of rapidminer process not successful.');
                    return redirect('/');
                }
            }

        }
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
                $part = str_replace(' ', '+', $this->terms[$i]['value']);
                $query .= (strlen($query) > 0 ? '+' : '') . ($is_phrase ? '"' : '') . $part . ($is_phrase ? '"' : '');
            }
        }
        $query .= '+software';

        return $query;
    }

    public function load_process_status()
    {
        if (!$this->results_loaded) {
            $status = RapidMinerController::getJobStatus();
            if ($status == 'FINISHED' || $status == 'ERROR') {
                $this->process_finished = true;
                $this->results_loaded = true;
            } else
                $this->process_finished = false;
        }
    }


}
