<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;

class Detail extends Component
{
    public Customer $customer;

    public function mount($id)
    {
        $this->customer = Customer::with(['transactions' => function ($query) {
            $query->latest();
        }])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.customer.detail')->layout('layouts.app');
    }
}
