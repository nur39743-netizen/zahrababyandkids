<?php

namespace App\Livewire\Purchase;

use Livewire\Component;
use App\Models\Purchase;
use App\Models\ProductItem;

class Index extends Component
{
    public function render()
    {
        $purchases = Purchase::with('supplier')
            ->orderBy('purchase_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('livewire.purchase.index', [
            'purchases' => $purchases
        ])->layout('layouts.app');
    }

    public function delete($id)
    {
        $purchase = Purchase::with('purchaseItems')->find($id);
        if ($purchase) {
            if ($purchase->status === 'received') {
                foreach ($purchase->purchaseItems as $item) {
                    $pItem = ProductItem::find($item->product_item_id);
                    if ($pItem) {
                        $pItem->stok_akhir -= $item->quantity;
                        $pItem->save();
                    }
                }
            }
            $purchase->delete();
            session()->flash('success', 'Transaksi pembelian berhasil dihapus.');
        }
    }
}
