<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;

class Edit extends Component
{
    public Customer $customer;
    public $nama_customer;
    public $no_whatsapp;
    public $alamat;
    public $catatan;

    public function mount($id)
    {
        $this->customer = Customer::findOrFail($id);
        $this->nama_customer = $this->customer->nama_customer;
        $this->no_whatsapp = $this->customer->no_whatsapp;
        $this->alamat = $this->customer->alamat;
        $this->catatan = $this->customer->catatan;
    }

    public function save()
    {
        $this->validate([
            'nama_customer' => 'required|string|max:255|unique:customers,nama_customer,' . $this->customer->id,
            'no_whatsapp' => 'nullable|string|max:30',
            'alamat' => 'nullable|string|max:500',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $this->customer->update([
            'nama_customer' => $this->nama_customer,
            'no_whatsapp' => $this->no_whatsapp,
            'alamat' => $this->alamat,
            'catatan' => $this->catatan,
        ]);

        return redirect('/customers/' . $this->customer->id)->with('success', 'Data customer berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.customer.edit')->layout('layouts.app');
    }
}
