<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    protected $table = 'merchant';

    protected $fillable = [
        'user_id',
        'merchant_kategori_id',
        'name',
        'status',
    ];

    public function kategori()
    {
        return $this->belongsTo(MerchantKategori::class, 'merchant_kategori_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(MerchantItem::class, 'merchant_id');
    }

    public function addresses()
    {
        return $this->hasMany(MerchantAddress::class, 'merchant_id');
    }
}
