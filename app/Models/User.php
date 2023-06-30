<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPUnit\Util\Json;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'email', 'password', 'phone', 'avatar', 'dob', 'role_id', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'full_name' => 'array'
    ];
    /**
     * @return HasMany
     */
    public function userAccess() {
        return $this->hasMany(UserAccess::class,'user_id', 'id');

    }

    public static function findOrCreate($email) {
        $obj = static::where('email', $email)->first();
        return $obj ?: false;
    }

    public function lastMailSent(){
        return $this->hasOne(SentMailLog::class)->latest();
    }

}
