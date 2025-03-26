<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pangkalan extends Model
{
    use HasFactory;

    protected $table = 'tb_pangkalan';

    protected $fillable = [
        'pangkalan_name',
        'pangkalan_address',
        'transaction_quota',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
