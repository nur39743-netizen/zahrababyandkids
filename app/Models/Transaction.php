<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    public const STATUS_PEMBAYARAN_LUNAS = 'lunas';

    public const STATUS_PEMBAYARAN_BELUM = 'belum_lunas';

    protected $guarded = [];
    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    protected static function booted()
    {
        static::deleting(function (Transaction $transaction) {
            // Restore stock when a transaction is soft deleted.
            if (! $transaction->isForceDeleting()) {
                foreach ($transaction->items as $item) {
                    if ($item->productItem) {
                        $item->productItem->increment('stok_akhir', $item->qty);
                    }
                }
            }

            // If force deleting from an active transaction, restore stock first as well.
            if ($transaction->isForceDeleting() && ! $transaction->trashed()) {
                foreach ($transaction->items()->withTrashed()->get() as $item) {
                    if ($item->productItem) {
                        $item->productItem->increment('stok_akhir', $item->qty);
                    }
                }
                $transaction->items()->withTrashed()->forceDelete();
            } elseif ($transaction->isForceDeleting()) {
                $transaction->items()->withTrashed()->forceDelete();
            } else {
                $transaction->items()->delete();
            }
        });

        static::restoring(function (Transaction $transaction) {
            // Reapply stock reductions when restoring a soft deleted transaction.
            foreach ($transaction->items()->withTrashed()->get() as $item) {
                if ($item->productItem) {
                    $item->productItem->decrement('stok_akhir', $item->qty);
                }
            }
            $transaction->items()->withTrashed()->restore();
        });
    }
}
