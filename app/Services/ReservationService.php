<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Service;
use App\Models\Slot;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationService
{
    public function store(Request $request)
    {
        $this->validateRequest($request);

        $reservation = $this->createReservation($request);

        $this->attachServicesToReservation($request, $reservation);

        $this->createSlotsForReservation($request, $reservation);

        return $reservation;
    }

    private function validateRequest(Request $request)
    {
        $request->validate([
            'barber_id' => 'required|exists:barbers,id',
            'start_time' => 'required',
            'services' => 'required|array|min:1',
            'services.*.name' => 'required|string|exists:services,name',
        ]);
    }

    private function createReservation(Request $request)
    {
        return Reservation::create([
            'barber_id' => $request->barber_id,
            'user_id' => auth()->user()->id,
        ]);
    }

    private function attachServicesToReservation(Request $request, Reservation $reservation)
    {
        $services = $request->input('services');
        foreach ($services as $service) {
            $current_service = Service::where('name', $service['name'])->first();
            $current_service->reservations()->attach($reservation->id);
        }
    }

    private function createSlotsForReservation(Request $request, Reservation $reservation)
    {
        $services = $request->input('services');
        $startTime = $request->input('start_time');
        $start = Carbon::parse($startTime);

        foreach ($services as $service) {
            $current_service = Service::where('name', $service['name'])->first();
            $numberOfSlots = $current_service->slots;

            for ($i = 0; $i < $numberOfSlots; $i++) {
                $intervalEnd = $start->copy()->addMinutes(15);
                $slot = Slot::create([
                    'start_time' => $start->format('Y-m-d H:i:s'),
                    'end_time' => $intervalEnd->format('Y-m-d H:i:s'),
                    'barber_id' => $request->barber_id,
                    'reservation_id' => $reservation->id,
                ]);
                $start = $intervalEnd;
            }
        }
    }
}
