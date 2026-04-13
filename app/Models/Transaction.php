<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $guarded = [];

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
            if ($transaction->isForceDeleting()) {
                $transaction->items()->withTrashed()->forceDelete();
            } else {
                $transaction->items()->delete();
            }
        });

        static::restoring(function (Transaction $transaction) {
            $transaction->items()->withTrashed()->restore();
        });
    }
}
