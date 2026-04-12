<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use App\Models\Owner;

class Index extends Component
{
    public $nama_owner;
    
    public function save()
    {
        $this->validate(['nama_owner' => 'required|string|max:255|unique:owners,nama_owner']);
        Owner::create([
            'nama_owner' => $this->nama_owner,
        ]);
        $this->reset('nama_owner');
    }
    
    public function delete($id)
    {
        Owner::find($id)->delete();
    }

    public function render()
    {
        return view('livewire.owner.index', [
            'owners' => Owner::withCount('products')->orderBy('nama_owner')->get()
        ]);
    }
}
