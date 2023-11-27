<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'tbl_cart';

    protected $fillable = [
        'id_barang',
        'id_user',
        'barang',
        'harga',
        'qty',
        'no_transaksi',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
