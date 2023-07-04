<?php

namespace App\Repositories;

use App\Models\Slot;

class SlotRepository
{
    public function getAll()
    {
        return Slot::all();
    }

    public function getById($id)
    {
        return Slot::findOrFail($id);
    }

    public function getReservedSlotsByDayAndBarber($day, $barberId)
    {
        return Slot::where('start_time', 'LIKE', $day . '%')
            ->where('barber_id', $barberId)
            ->get();
    }

    public function create(array $data)
    {
        return Slot::create($data);
    }

    public function delete(Slot $slot)
    {
        $slot->delete();
    }

    public function getByIdAndBarber($slotId, $barberId)
    {
        return Slot::where('id', $slotId)
            ->where('barber_id', $barberId)
            ->firstOrFail();
    }

    public function getOverlappingSlot($start, $end, $barberId)
    {
        return Slot::where('barber_id', $barberId)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_time', [$start, $end])
                    ->orWhereBetween('end_time', [$start, $end])
                    ->orWhere(function ($query) use ($start, $end) {
                        $query->where('start_time', '<=', $start)
                            ->where('end_time', '>=', $end);
                    });
            })
            ->first();
    }

    public function getSlotByStartAndEnd($start, $end, $barberId)
    {
        return Slot::where('barber_id', $barberId)
            ->where('start_time', $start)
            ->where('end_time', $end)
            ->first();
    }
}