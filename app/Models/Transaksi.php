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
        'nik',
        'nik_type',
        'user_id',
        'pangkalan_id',
        'created_at',
        'updated_at'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pangkalan(){
        return $this->belongsTo(Pangkalan::class, 'pangkalan_id', 'id');
    }

}
