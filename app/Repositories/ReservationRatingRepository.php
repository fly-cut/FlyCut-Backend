<?php

namespace App\Repositories;

use App\Models\ReservationRating;
use App\Models\Reservation;
use App\Models\Barber;
use App\Models\Barbershop;

class ReservationRatingRepository
{
    public function getAll()
    {
        return ReservationRating::all();
    }

    public function getById($id)
    {
        return ReservationRating::find($id);
    }

    public function create($data)
    {
        return ReservationRating::create($data);
    }

    public function update($id, $data)
    {
        $reservationRating = ReservationRating::find($id);
        $reservationRating->update($data);
        return $reservationRating;
    }

    public function delete($id)
    {
        $reservationRating = ReservationRating::find($id);
        $reservationRating->delete();
    }

    public function getBarberRatings($id)
    {
        return ReservationRating::where('barber_id', $id)->get();
    }

    public function getBarbershopRatings($id)
    {
        return ReservationRating::where('barbershop_id', $id)->get();
    }

    public function getBarberRatingByReservationId($id)
    {
        return ReservationRating::where('reservation_id', $id)->first();
    }
}
