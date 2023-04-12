<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $guard = 'admin';
    protected $fillable = [
        'name',
        'email',
        'password'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
