<?php

namespace App\Services;

use App\Models\Service;
use App\Models\Variation;
use App\Models\Reservation;

class ReservationServiceAttachment
{
    public function attachServicesToReservation($request, Reservation $reservation)
    {
        $services = $request['services'];

        foreach ($services as $service) {
            $currentService = Service::where('name', $service['name'])->first();

            if (isset($service['variation_name'])) {
                $serviceVariation = Variation::where('name', $service['variation_name'])->first();
                $variationId = $serviceVariation->id;
                $currentService->reservations()->attach($reservation->id, ['variation_id' => $variationId]);
            } else {
                $currentService->reservations()->attach($reservation->id);
            }
        }
    }
}
