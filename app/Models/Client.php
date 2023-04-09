<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Client extends Authenticatable
{
    use HasApiTokens, notifiable;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'image',
        'birth_date'
    ];
    use HasFactory;
}
