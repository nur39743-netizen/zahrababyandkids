<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantOption extends Model
{
    protected $guarded = [];
    public function attribute() { return $this->belongsTo(VariantAttribute::class, 'attribute_id'); }
}
