<?php

namespace App\Livewire\Transaction;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public $month = '';

    public function mount()
    {
        $this->month = date('Y-m');
    }

    public function updatingMonth()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Transaction::with('customer')
            ->orderByDesc('transaction_date')
            ->orderByDesc('created_at');

        if ($this->month) {
            try {
                $parsedDate = Carbon::createFromFormat('Y-m', $this->month);
                $query->whereYear('transaction_date', $parsedDate->year)
                    ->whereMonth('transaction_date', $parsedDate->month);
            } catch(\Exception $e){}
        }

        return view('livewire.transaction.index', [
            'transactions' => $query->paginate(15)
        ])->layout('layouts.app');
    }
}
