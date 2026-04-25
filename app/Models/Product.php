<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'total_terjual' => 'integer',
    ];

    /**
     * Total qty terjual (semua varian), dari transaction_items yang belum dihapus.
     */
    public function scopeWithTotalTerjual(Builder $query): Builder
    {
        // addSelect saja (tanpa select * terlebih dahulu) menggantikan SELECT * menjadi
        // hanya kolom agregat, sehingga kode_produk, category_id, dll. hilang dari hasil.
        if ($query->getQuery()->columns === null) {
            $query->select($query->getModel()->getTable() . '.*');
        }

        return $query->addSelect(DB::raw('(
            SELECT COALESCE(SUM(ti.qty), 0)
            FROM transaction_items ti
            INNER JOIN product_items pi ON pi.id = ti.product_item_id
            WHERE pi.product_id = products.id AND ti.deleted_at IS NULL
        ) as total_terjual'));
    }

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
