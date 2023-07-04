<?php

namespace App\Repositories;

use App\Models\Slot;
use App\Models\Barber;

class BarberRepository
{
    public function create(array $data)
    {
        return Barber::create($data);
    }

    public function find($barberId)
    {
        return Barber::find($barberId);
    }

    public function save($barber)
    {
        $barber->save();
    }

    public function delete($barberId)
    {
        Barber::destroy($barberId);
    }

    public function getSlotsInRange($barberId, $startTime, $endTime)
    {
        return Slot::where('barber_id', $barberId)
            ->where('start_time', '>=', $startTime)
            ->where('end_time', '<=', $endTime)
            ->orderBy('start_time')
            ->get();
    }
}
