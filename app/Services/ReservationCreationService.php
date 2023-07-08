<?php

namespace App\Services;

use Carbon\Carbon;
use App\Repositories\ReservationRepository;

class ReservationCreationService
{
    private $reservationRepository;

    public function __construct(ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function createReservation($request)
    {
        $dateString = $request['start_time'];
        $timeNow = Carbon::now('Africa/Cairo');
        $curret_time = Carbon::parse($timeNow)->subHours(12)->addHour();

        if ($dateString < $curret_time) {
            throw new \Exception('You cannot make a reservation in the past');
        }

        $reservationData = [
            'barber_id' => $request['barber_id'],
            'user_id' => auth()->user()->id,
            'barbershop_id' => $request['barbershop_id'],
            'date' => $dateString,
        ];

        return $this->reservationRepository->create($reservationData);
    }
}
