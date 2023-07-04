<?php

namespace App\Repositories;

use App\Models\ReservationRating;
use App\Models\Reservation;
use App\Models\Barber;
use App\Models\Barbershop;
use App\Models\Client;
use App\Models\Service;
use Illuminate\Support\Facades\DB;


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

    public function create($request)
    {
        $reservationRating = ReservationRating::create($request->all());
        $reservation = Reservation::find($request->reservation_id);
        $reservation->is_rated = true;
        $reservation->save();
        $barber = Barber::find($request->barber_id);
        $barber->rating = ReservationRating::where('barber_id', $request->barber_id)->avg('barber_rating');
        $barber->rating_count = ReservationRating::where('barber_id', $request->barber_id)->count();
        $barber->save();

        $barbershop = Barbershop::find($request->barbershop_id);
        $barbershop->rating = ReservationRating::where('barbershop_id', $request->barbershop_id)->avg('barbershop_rating');
        $barbershop->rating_count = ReservationRating::where('barbershop_id', $request->barbershop_id)->count();
        $barbershop->save();
        return $reservationRating;
    }

    public function update($request, $id)
    {
        $reservationRating = ReservationRating::where('reservation_id', $id)->first();
        $reservationRating->update($request->all());

        $barber = Barber::find($reservationRating->barber_id);
        $barber->rating = ReservationRating::where('barber_id', $reservationRating->barber_id)->avg('barber_rating');
        $barber->rating_count = ReservationRating::where('barber_id', $reservationRating->barber_id)->count();
        $barber->save();

        $barbershop = Barbershop::find($reservationRating->barbershop_id);
        $barbershop->rating = ReservationRating::where('barbershop_id', $reservationRating->barbershop_id)->avg('barbershop_rating');
        $barbershop->rating_count = ReservationRating::where('barbershop_id', $reservationRating->barbershop_id)->count();
        $barbershop->save();
        return $reservationRating;
    }

    public function delete($id)
    {
        $reservationRating = ReservationRating::find($id);
        $barber = Barber::find($reservationRating->barber_id);
        $barbershop = Barbershop::find($reservationRating->barbershop_id);
        $reservation = Reservation::find($reservationRating->reservation_id);
        $reservation->is_rated = false;
        $reservationRating->delete();
        if (ReservationRating::where('barbershop_id', $barbershop->barbershop_id)->count() > 0) {
            $barbershop->rating = ReservationRating::where('barbershop_id', $barbershop->barbershop_id)->avg('barbershop_rating');
        } else {
            $barbershop->rating = 5.0;
        }

        if (ReservationRating::where('barber_id', $barber->barber_id)->count() > 0) {
            $barber->rating = ReservationRating::where('barber_id', $barber->barber_id)->avg('barber_rating');
        } else {
            $barber->rating = 5.0;
        }
        $barbershop->rating_count = ReservationRating::where('barbershop_id', $barbershop->barbershop_id)->count();
        $barber->rating_count = ReservationRating::where('barber_id', $barber->barber_id)->count();
        $barbershop->save();
        $barber->save();
    }

    public function getBarberRatings($id)
    {
        $barberRatings = ReservationRating::where('barber_id', $id)->get();
        foreach ($barberRatings as $rating) {
            $rating->client_name = Client::find($rating->client_id)->name;
            $rating->client_image = Client::find($rating->client_id)->image;
        }
        return $barberRatings;
    }

    public function getBarbershopRatings($id)
    {
        $barbershopRatings = ReservationRating::where('barbershop_id', $id)->get();
        foreach ($barbershopRatings as $rating) {
            $rating->client_name = Client::find($rating->client_id)->name;
            $rating->client_image = Client::find($rating->client_id)->image;
            $rating->barber_name = Barber::find($rating->barber_id)->name;
            $rating->barber_image = Barber::find($rating->barber_id)->image;
            $rating->reservation_date = Reservation::find($rating->reservation_id)->date;
            $services = [];
            $services_ids = DB::table('reservation_service')->where('reservation_id', $rating->reservation_id)->pluck('service_id');
            foreach ($services_ids as $service_id) {
                $service = Service::find($service_id);
                array_push($services, $service);
            }
            $rating->services = $services;
        }
        return $barbershopRatings;
    }

    public function getBarberRatingByReservationId($id)
    {
        $rating = ReservationRating::where('reservation_id', $id)->first();
        $rating->client_name = Client::find($rating->client_id)->name;
        $rating->client_image = Client::find($rating->client_id)->image;
        $rating->barber_name = Barber::find($rating->barber_id)->name;
        $services = [];
        $services_ids = DB::table('reservation_service')->where('reservation_id', $rating->reservation_id)->pluck('service_id');
        foreach ($services_ids as $service_id) {
            $service = Service::find($service_id);
            array_push($services, $service);
        }
        $rating->services = $services;
        return $rating;
    }
}