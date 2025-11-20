<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantAddress extends Model
{
    use HasFactory;

    protected $table = 'merchant_address';

    protected $fillable = [
        'merchant_id',
        'alamat',
        'latitude',
        'longitude',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }
}
