<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;

class Index extends Component
{
    public $nama_customer;

    public function save()
    {
        $this->validate(['nama_customer' => 'required|string|max:255|unique:customers,nama_customer']);
        Customer::create([
            'nama_customer' => $this->nama_customer,
        ]);
        $this->reset('nama_customer');
    }

    public function delete($id)
    {
        Customer::find($id)->delete();
    }

    public function render()
    {
        return view('livewire.customer.index', [
            'customers' => Customer::withCount('transactions')->orderBy('nama_customer')->get()
        ]);
    }
}
