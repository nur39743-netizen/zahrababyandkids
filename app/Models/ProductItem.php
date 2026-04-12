<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductItem extends Model
{
    protected $guarded = [];

    public function product() { return $this->belongsTo(Product::class); }
    public function variantOption1() { return $this->belongsTo(VariantOption::class, 'variant_option_1_id'); }
    public function variantOption2() { return $this->belongsTo(VariantOption::class, 'variant_option_2_id'); }
}
