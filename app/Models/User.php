<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Subscription;
use App\Models\Pangkalan;

class User extends Authenticatable
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'tb_user';

    protected $fillable = [
        'user_name',
        'email',
        'password',
        'role'
    ];

    public function subscriptions() {
        return $this->hasMany(Subscription::class, 'user_id', 'id');
    }

    public function pangkalan() {
        return $this->hasOne(Pangkalan::class, 'user_id', 'id');
    }

    public function registeredBy(){
        return $this->hasMany(Subscription::class, 'registered_by', 'id');
    }

}
