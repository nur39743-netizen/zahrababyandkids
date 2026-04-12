<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use App\Models\Owner;

class Edit extends Component
{
    public Owner $owner;
    public $nama_owner;

    public function mount(Owner $owner)
    {
        $this->owner = $owner;
        $this->nama_owner = $owner->nama_owner;
    }

    public function save()
    {
        $this->validate([
            'nama_owner' => 'required|string|max:255|unique:owners,nama_owner,' . $this->owner->id,
        ]);

        $this->owner->update([
            'nama_owner' => $this->nama_owner,
        ]);

        return redirect()->to('/owners');
    }

    public function render()
    {
        return view('livewire.owner.edit')->layout('layouts.app');
    }
}
