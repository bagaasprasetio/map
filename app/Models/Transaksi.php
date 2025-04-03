<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pangkalan;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'tb_transaction';

    protected $fillable = [
        'transaction_date',
        'nik',
        'nik_type',
        'user_id',
        'pangkalan_id',
        'created_at',
        'updated_at'
    ];

}
