<?php

namespace App\Http\Livewire;

use App\Models\IndustrySelection;
use Livewire\Component;

class Settings extends Component
{
    public $industry_fields;
    public $max_industries;

    public function mount()
    {
        $settings = IndustrySelection::orderBy('created_at','desc')->first();
        if ($settings) {
            $this->max_industries = $settings->max_industries;
            $this->industry_fields = json_decode($settings->industries);
        } else {
            $this->max_industries = 2; // Todo decide if dynamically increaseable
            $this->industry_fields = [['value' => ''], ['value' => '']];
        }
    }

    public function render()
    {
        return view('livewire.settings');
    }

    public function save()
    {
        $industries_to_save = [];
        foreach ($this->industry_fields as $industry_field) {
            if(strlen($industry_field['value']) > 0)
                array_push($industries_to_save, $industry_field);
        }

        $settings = new IndustrySelection([
            'industries' => json_encode($industries_to_save),
            'max_industries' => $this->max_industries
        ]);

        $settings->save();

        $this->redirect('/settings');

    }
}
