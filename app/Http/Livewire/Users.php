<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class Users extends Component
{
    public $name;
    public $users;

    protected $rules = [
        'name' => 'required|min:3'
    ];

    public function render()
    {
        return view('livewire.users');
    }

    public function mount()
    {
        $this->users = User::all();
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        $this->users = User::all();
    }

    public function submit()
    {
        $this->validate();

        User::create(
            ['name' => $this->name]
        );
        $this->name = '';
        $this->users = User::all();
    }

}
