<?php

namespace App\Livewire\Variant;

use Livewire\Component;
use App\Models\VariantAttribute;
use App\Models\VariantOption;

class Index extends Component
{
    public $nama_attribute = '';
    public $new_options = []; // Array of attribute_id => new option value

    public function createAttribute()
    {
        $this->validate(['nama_attribute' => 'required|string|max:255|unique:variant_attributes,name']);
        VariantAttribute::create(['name' => $this->nama_attribute]);
        $this->nama_attribute = '';
    }

    public function deleteAttribute($id)
    {
        VariantAttribute::find($id)->delete();
    }

    public function createOption($attribute_id)
    {
        $val = $this->new_options[$attribute_id] ?? '';
        if(empty(trim($val))) return;

        VariantOption::create([
            'attribute_id' => $attribute_id,
            'value' => trim($val)
        ]);

        $this->new_options[$attribute_id] = '';
    }

    public function deleteOption($id)
    {
        VariantOption::find($id)->delete();
    }

    public function render()
    {
        return view('livewire.variant.index', [
            'variantData' => VariantAttribute::with('options')->get()
        ])->layout('layouts.app');
    }
}
