<?php

namespace App\Http\Livewire;

use App\Models\IndustrySelection;
use Livewire\Component;

class Settings extends Component
{
    public $industry_fields;

    public function mount()
    {
        $settings = IndustrySelection::orderBy('created_at','desc')->first();
        if ($settings) {
            $this->industry_fields = json_decode($settings->industries);
        } else {
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
            if(strlen(trim($industry_field['value'])) > 0)
                array_push($industries_to_save, $industry_field);
        }
        $settings = new IndustrySelection([
            'industries' => json_encode($industries_to_save),
        ]);
        $settings->save();
        $this->redirect('/settings');
    }
}
