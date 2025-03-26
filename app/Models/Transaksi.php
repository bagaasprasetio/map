<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'tb_transaction';

    protected $fillable = [
        'transaction_date',
        'transaction_total',
        'nik_type',
        'transaction_status',
        'user_id',
        'pangkalan_id'
    ];
}
