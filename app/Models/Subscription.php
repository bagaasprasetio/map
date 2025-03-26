<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'tb_subscription';

    protected $fillable = [
        'subs_start',
        'subs_end',
        'registered_by',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function registeredBy(){
        return $this->belongsTo(User::class, 'registered_by', 'id');
    }
}
