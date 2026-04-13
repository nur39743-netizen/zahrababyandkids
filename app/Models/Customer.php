<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    protected $table = 'customers';

    protected $fillable = [
        'nama_customer',
        'no_whatsapp',
        'alamat',
        'catatan',
    ];

    protected $guarded = [];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
