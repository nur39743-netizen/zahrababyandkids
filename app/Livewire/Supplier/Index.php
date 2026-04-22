<?php

namespace App\Livewire\Supplier;

use Livewire\Component;
use App\Models\Supplier;

class Index extends Component
{
    public $name, $contact_person, $phone, $address, $notes, $supplier_id;
    public $isEdit = false;
    public $showModal = false;

    public function render()
    {
        return view('livewire.supplier.index', [
            'suppliers' => Supplier::orderBy('name')->get()
        ])->layout('layouts.app');
    }

    public function openModal()
    {
        $this->resetInput();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInput();
    }

    public function resetInput()
    {
        $this->name = null;
        $this->contact_person = null;
        $this->phone = null;
        $this->address = null;
        $this->notes = null;
        $this->supplier_id = null;
        $this->isEdit = false;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($this->isEdit) {
            $supplier = Supplier::find($this->supplier_id);
            $supplier->update([
                'name' => $this->name,
                'contact_person' => $this->contact_person,
                'phone' => $this->phone,
                'address' => $this->address,
                'notes' => $this->notes,
            ]);
        } else {
            Supplier::create([
                'name' => $this->name,
                'contact_person' => $this->contact_person,
                'phone' => $this->phone,
                'address' => $this->address,
                'notes' => $this->notes,
            ]);
        }

        $this->closeModal();
    }

    public function edit($id)
    {
        $supplier = Supplier::find($id);
        $this->supplier_id = $supplier->id;
        $this->name = $supplier->name;
        $this->contact_person = $supplier->contact_person;
        $this->phone = $supplier->phone;
        $this->address = $supplier->address;
        $this->notes = $supplier->notes;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        Supplier::find($id)->delete();
    }
}
