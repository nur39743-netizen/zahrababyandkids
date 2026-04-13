<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    protected static function booted()
    {
        static::deleting(function (Category $category) {
            if ($category->isForceDeleting()) {
                $category->products()->withTrashed()->forceDelete();
            } else {
                $category->products()->delete();
            }
        });

        static::restoring(function (Category $category) {
            $category->products()->withTrashed()->restore();
        });
    }
}
