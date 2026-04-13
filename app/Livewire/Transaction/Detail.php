<?php

namespace App\Livewire\Transaction;

use Livewire\Component;
use App\Models\Transaction;

class Detail extends Component
{
    public $transaction;

    public function mount($id)
    {
        $this->transaction = Transaction::with(['customer', 'items.productItem.product', 'items.productItem.variantOption1', 'items.productItem.variantOption2'])->findOrFail($id);
    }

    public function delete()
    {
        $this->transaction->delete();
        return redirect('/transactions')->with('success', 'Transaksi berhasil dinonaktifkan.');
    }

    public function render()
    {
        return view('livewire.transaction.detail')->layout('layouts.app');
    }
}
