<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\TransactionItem;

class ProductItem extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function variantOption1()
    {
        return $this->belongsTo(VariantOption::class, 'variant_option_1_id');
    }
    public function variantOption2()
    {
        return $this->belongsTo(VariantOption::class, 'variant_option_2_id');
    }
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function variantString(): string
    {
        $parts = [];

        if ($this->variantOption1?->value) {
            $parts[] = $this->variantOption1->value;
        }

        if ($this->variantOption2?->value) {
            $parts[] = $this->variantOption2->value;
        }

        return count($parts) ? implode(' / ', $parts) : 'Standard';
    }
}
