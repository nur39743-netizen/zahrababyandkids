<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use App\Models\Owner;

class Detail extends Component
{
    public Owner $owner;

    public function mount($id)
    {
        $this->owner = Owner::with(['products' => function ($query) {
            $query->withCount('items')->latest();
        }])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.owner.detail')->layout('layouts.app');
    }
}
