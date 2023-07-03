<?php

namespace App\Services;

use App\Models\Slot;
use Carbon\Carbon;

class SlotAvailabilityService
{
    public function checkAvailability($startTime, $numberOfSlots, $barberId)
    {
        $startTime = Carbon::parse($startTime);
        $endTime = $startTime->copy()->addMinutes($numberOfSlots * 15);

        $existingSlots = $this->getExistingSlots($startTime, $endTime, $barberId);

        $isAvailable = $this->validateSlotsAvailability($existingSlots, $endTime);

        return $isAvailable;
    }

    private function getExistingSlots($startTime, $endTime, $barberId)
    {
        return Slot::where('barber_id', $barberId)
            ->where('start_time', '>=', $startTime)
            ->where('end_time', '<=', $endTime)
            ->orderBy('start_time')
            ->get();
    }

    private function validateSlotsAvailability($existingSlots, $endTime)
    {
        $prevEndTime = null;

        foreach ($existingSlots as $slot) {
            if ($slot->start_time > $endTime) {
                break;
            }

            if ($slot->start_time > $prevEndTime) {
                return false;
            }

            $prevEndTime = $slot->end_time;
        }

        return true;
    }
}
