<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\ProductItem;
use Carbon\Carbon;

class Index extends Component
{
    public $filterMonth;

    public function mount()
    {
        $this->filterMonth = date('Y-m');
    }

    public function render()
    {
        $today = Carbon::today();
        
        try {
            $selectedDate = Carbon::createFromFormat('Y-m', $this->filterMonth ?? date('Y-m'));
        } catch(\Exception $e) {
            $selectedDate = Carbon::now();
            $this->filterMonth = date('Y-m');
        }

        $thisMonth = $selectedDate->month;
        $thisYear = $selectedDate->year;

        // Omset (Kotor) Hari Ini
        $omsetHariIni = Transaction::whereDate('created_at', $today)->sum('total_netto');
        
        // Transaksi Bulan Ini
        $trxBulanIni = Transaction::whereMonth('created_at', $thisMonth)
                                  ->whereYear('created_at', $thisYear);
        $totalTransaksiBulanIni = $trxBulanIni->count();
        $omsetBulanIni = $trxBulanIni->sum('total_netto');

        // Laba bersih: hanya transaksi lunas & tidak dibatalkan (omset tetap semua trx bulan tersebut)
        $labaKotorItemsBulanIni = TransactionItem::whereHas('transaction', function ($q) use ($thisMonth, $thisYear) {
            $q->whereMonth('created_at', $thisMonth)
                ->whereYear('created_at', $thisYear)
                ->where('status_pembayaran', Transaction::STATUS_PEMBAYARAN_LUNAS)
                ->where('status', '!=', 'Dibatalkan');
        })->get()->sum(function ($item) {
            return ($item->harga_jual_history - $item->harga_modal_history) * $item->qty;
        });
                                  
        $diskonGlobalBulanIni = (clone $trxBulanIni)
            ->where('status_pembayaran', Transaction::STATUS_PEMBAYARAN_LUNAS)
            ->where('status', '!=', 'Dibatalkan')
            ->sum('total_diskon');
        $labaBersihBulanIni = $labaKotorItemsBulanIni - $diskonGlobalBulanIni;

        return view('livewire.dashboard.index', [
            'omsetHariIni' => $omsetHariIni,
            'omsetBulanIni' => $omsetBulanIni,
            'totalTransaksiBulanIni' => $totalTransaksiBulanIni,
            'labaBersihBulanIni' => $labaBersihBulanIni,
            'barangMenipis' => ProductItem::with(['product', 'variantOption1', 'variantOption2'])->where('stok_akhir', '<=', 5)->where('stok_akhir', '>', 0)->count()
        ])->layout('layouts.app');
    }
}
