<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Client extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'birth_date'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function providers()
    {
        return $this->hasMany(ClientProviders::class, 'client_id', 'id');
    }
}
