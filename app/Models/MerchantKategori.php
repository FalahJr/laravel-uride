<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantKategori extends Model
{
    use HasFactory;

    protected $table = 'merchant_kategori';

    protected $fillable = ['name'];

    public function merchants()
    {
        return $this->hasMany(Merchant::class, 'merchant_kategori_id');
    }
}
