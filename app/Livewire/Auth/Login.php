<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Login extends Component
{
    public $email;
    public $password;
    public $remember = false;

    public function mount()
    {
        if (Auth::check()) {
            return redirect()->to('/');
        }
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            return redirect()->to('/');
        }

        $this->addError('email', 'Kredensial yang diberikan tidak cocok dengan data kami.');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.auth.login');
    }
}
