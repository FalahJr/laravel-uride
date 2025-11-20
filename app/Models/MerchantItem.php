<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantItem extends Model
{
    use HasFactory;

    protected $table = 'merchant_item';

    protected $fillable = [
        'merchant_id',
        'nama_barang',
        'harga',
        'stock',
        'description',
        'image_url',
        'is_available',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }
}
