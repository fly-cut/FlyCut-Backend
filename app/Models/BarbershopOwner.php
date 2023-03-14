<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarbershopOwner extends Model
{
    use HasFactory;


    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'birth_date',
        'image'
    ];

    public function barbershop()
    {
        return $this->hasOne(Barbershop::class);
    }
}
