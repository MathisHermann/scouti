<?php

namespace App\Http\Livewire;

use App\Http\Controllers\RapidMinerController;
use App\Http\Controllers\SearchEngineController;
use App\Models\IndustrySelection;
use App\Models\SearchEngineRequest;
use App\Models\SearchEngineResult;
use Livewire\Component;
use Session;

class QuerySelection extends Component
{

    public $terms;
    public $industries;
    public $industry;
    public $modal_msg;
    public $ignore = false;
    public $default_dropdown = 'Select';

    public function mount()
    {
        // TODO: remove the values if not needed anymore!
        $this->terms = [['value' => 'antivirus']];
        $temp_industries = IndustrySelection::latest()->first();
        if ($temp_industries != null)
            $this->industries = json_decode($temp_industries->industries, true);
        else
            $this->industries = [];
        $this->modal_msg = '';
        $this->industry = $this->default_dropdown;
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
        if ($this->industry == $this->default_dropdown) {
            Session::flash('error_dropdown_selection', 'Please select an industry option.');
            return redirect('/');
        } else {

            $query = $this->make_string();

            // TODO: Change modal_msg according to state
            $this->modal_msg = 'Fetching Google SE Results';

            // TODO: Catch errors and make information accordingly
            $result = SearchEngineController::getSearchEngineLinks($query);
            $this->modal_msg = 'Processing Links';
            $result = SearchEngineController::getFormattedURLs($result);

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
        //$is_phrase = strpos($this->industry, ' ');
        //$query .= '+' . ($is_phrase ? '"' : '') . str_replace(' ', '+', $this->industry) . ($is_phrase ? '"' : '');
        //$query .= '+' . str_replace(' ', '+', $this->industry);
        $query .= '+software';

        return $query;
    }

}
