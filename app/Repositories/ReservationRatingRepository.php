<?php

namespace App\Repositories;

use App\Models\ReservationRating;
use Illuminate\Support\Facades\DB;

class ReservationRatingRepository
{
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
        ReservationRating::destroy($id);
    }

    /*public function getBarberRatings($id)
    {
        return ReservationRating::where('barber_id', $id)->get();
    }*/

    /*public function getBarbershopRatings($id)
    {
        return ReservationRating::where('barbershop_id', $id)->get();
    }*/

    public function getBarberRatingByReservationId($id)
    {
        return ReservationRating::where('reservation_id', $id)->first();
    }

    public function getBarbershopRatings($barbershopId)
    {
        return ReservationRating::where('barbershop_id', $barbershopId);
    }

    public function getBarberRatings($barberId)
    {
        return ReservationRating::where('barber_id', $barberId);
    }

    public function getAverageBarbershopRating($barbershopId)
    {
        return ReservationRating::where('barbershop_id', $barbershopId)->avg('barbershop_rating');
    }

    public function getAverageBarberRating($barberId)
    {
        return ReservationRating::where('barber_id', $barberId)->avg('barber_rating');
    }

    public function getBarbershopRatingCount($barbershopId)
    {
        return ReservationRating::where('barbershop_id', $barbershopId)->count();
    }

    public function getBarberRatingCount($barberId)
    {
        return ReservationRating::where('barber_id', $barberId)->count();
    }
}
