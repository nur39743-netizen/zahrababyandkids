<?php

namespace App\Livewire\Transaction;

use Livewire\Component;
use App\Models\Transaction;

class Trashed extends Component
{
    public function restore($id)
    {
        $transaction = Transaction::withTrashed()->find($id);
        if ($transaction) {
            $transaction->restore();
            $transaction->items()->withTrashed()->restore();
            session()->flash('success', 'Transaksi berhasil dikembalikan.');
        }
    }

    public function forceDelete($id)
    {
        $transaction = Transaction::withTrashed()->find($id);
        if ($transaction) {
            $transaction->items()->withTrashed()->forceDelete();
            $transaction->forceDelete();
            session()->flash('success', 'Transaksi berhasil dihapus permanen.');
        }
    }

    public function render()
    {
        return view('livewire.transaction.trashed', [
            'transactions' => Transaction::onlyTrashed()->with('customer')->orderBy('deleted_at', 'desc')->get()
        ])->layout('layouts.app');
    }
}
