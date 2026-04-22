<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function items()
    {
        return $this->hasMany(ProductItem::class);
    }

    protected static function booted()
    {
        static::deleting(function (Product $product) {
            if ($product->isForceDeleting()) {
                $product->items()->withTrashed()->forceDelete();
            } else {
                $product->items()->delete();
            }
        });

        static::restoring(function (Product $product) {
            $product->items()->withTrashed()->restore();
        });
    }
}
