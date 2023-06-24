<?php

namespace App\Services;

use App\Http\Controllers\BarberController;
use App\Models\Barber;
use App\Models\Barbershop;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\Slot;
use App\Models\Variation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationService
{
    public function store(Request $request)
    {
        $this->validateRequest($request);

        $availability = $this->checkBarberAvailability($request);

        if ($availability->getStatusCode() !== 200) {
            return response()->json(['message' => 'No slots available'], 422);
        }

        $reservation = $this->createReservation($request);
        $this->attachServicesToReservation($request, $reservation);
        $this->createSlotsForReservation($request, $reservation);

        return response()->json(['message' => 'Reservation created', 'reservation' => $reservation]);
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
        $dateString = $request->input('start_time');
        $dateTime = Carbon::parse($dateString);
        $date = $dateTime->format('Y-m-d');
        $checkAvailability = $this->checkBarberAvailability($request);
        return Reservation::create([
            'barber_id' => $request->barber_id,
            'user_id' => auth()->user()->id,
            'barbershop_id' => $request->barbershop_id,
            'date' => $date,
        ]);
    }

    private function checkBarberAvailability(Request $request)
    {
        $startTime = $request->input('start_time');
        $services = $request->input('services');
        $totalslots = 0;

        foreach ($services as $service) {
            $currentService = Service::where('name', $service['name'])->first();
            $id = $currentService->id;
            $barbershop = Barbershop::where('id', $request->barbershop_id)->first();
            foreach ($barbershop->services as $service) {
                if ($service->id == $id) {
                    $totalslots += $service->pivot->slots;
                }
            }
        }

        $barberId = $request->input('barber_id');
        $response = BarberController::checkAvailability($startTime, $totalslots, $barberId);
        return $response;
    }

    private function attachServicesToReservation(Request $request, Reservation $reservation)
    {
        $services = $request->input('services');
        foreach ($services as $service) {
            $current_service = Service::where('name', $service['name'])->first();
            if ($service['variation_name']) {
                $service_variation = Variation::where('name', $service['variation_name'])->first();
                $variaton_id = $service_variation->id;
                $current_service->reservations()->attach($reservation->id, ['variation_id' => $variaton_id]);
            } else {
                $current_service->reservations()->attach($reservation->id);
            }
        }
    }

    private function createSlotsForReservation(Request $request, Reservation $reservation)
    {
        $services = $request->input('services');

        $startTime = $request->input('start_time');
        $start = Carbon::parse($startTime);
        $total_price = 0;
        foreach ($services as $service) {
            $current_service = Service::where('name', $service['name'])->first();
            $id = $current_service->id;
            $barbershop = Barbershop::where('id', $request->barbershop_id)->first();
            $numberOfSlots = 0;
            foreach ($barbershop->services as $service) {
                if ($service->id == $id) {
                    $numberOfSlots = $service->pivot->slots;
                    $total_price += $service->pivot->price;
                }
            }
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
        $reservation->price = $total_price;
        $reservation->save();
    }
}
