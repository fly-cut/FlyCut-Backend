<?php

namespace App\Repositories;

use App\Models\Reservation;

class ReservationRepository
{
    public function create(array $data)
    {
        return Reservation::create($data);
    }
}
