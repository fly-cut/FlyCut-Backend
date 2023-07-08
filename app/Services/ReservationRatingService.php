<?php

namespace App\Services;

use App\Models\Barber;
use App\Models\Client;
use App\Models\Service;
use App\Models\Barbershop;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use App\Repositories\ReservationRatingRepository;

class ReservationRatingService
{
    private $reservationRatingRepository;

    public function __construct(ReservationRatingRepository $reservationRatingRepository)
    {
        $this->reservationRatingRepository = $reservationRatingRepository;
    }

    public function create($request)
    {
        $reservationRating = $this->reservationRatingRepository->create($request->all());

        if ($request->file('image')) {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('images/'), $image_name);
            $reservationRating->image = $image_name;
            $reservationRating->save();
        }

        $this->updateBarberRating($reservationRating->barber_id);
        $this->updateBarbershopRating($reservationRating->barbershop_id);

        return $reservationRating;
    }

    public function update($id, $request)
    {
        $reservationRating = $this->reservationRatingRepository->update($id, $request->all());

        $this->updateBarberRating($reservationRating->barber_id);
        $this->updateBarbershopRating($reservationRating->barbershop_id);

        return $reservationRating;
    }

    public function delete($id)
    {
        $reservationRating = $this->reservationRatingRepository->getById($id);

        $this->reservationRatingRepository->delete($id);
        $this->updateBarberRating($reservationRating->barber_id);
        $this->updateBarbershopRating($reservationRating->barbershop_id);
    }

    public function getBarberRatings($id)
    {
        $barberRatings = $this->reservationRatingRepository->getBarberRatings($id);

        foreach ($barberRatings as $rating) {
            $rating->client_name = Client::find($rating->client_id)->name;
            $rating->client_image = Client::find($rating->client_id)->image;
        }

        return $barberRatings;
    }

    public function getBarbershopRatings($id)
    {
        $barbershopRatings = $this->reservationRatingRepository->getBarbershopRatings($id);

        foreach ($barbershopRatings as $rating) {
            $rating->client_name = Client::find($rating->client_id)->name;
            $rating->client_image = Client::find($rating->client_id)->image;
            $rating->barber_name = Barber::find($rating->barber_id)->name;
            $rating->barber_image = Barber::find($rating->barber_id)->image;
            $rating->reservation_date = Reservation::find($rating->reservation_id)->date;
            $services = Service::whereIn(
                'id',
                DB::table('reservation_service')
                    ->where('reservation_id', $rating->reservation_id)
                    ->pluck('service_id')
            )->get();
            $rating->services = $services;
        }

        return $barbershopRatings;
    }

    public function getBarberRatingByReservationId($id)
    {
        $rating = $this->reservationRatingRepository->getBarberRatingByReservationId($id);

        $rating->client_name = Client::find($rating->client_id)->name;
        $rating->client_image = Client::find($rating->client_id)->image;
        $rating->barber_name = Barber::find($rating->barber_id)->name;
        $rating->services = Service::whereIn(
            'id',
            DB::table('reservation_service')
                ->where('reservation_id', $rating->reservation_id)
                ->pluck('service_id')
        )->get();

        return $rating;
    }

    private function updateBarberRating($barberId)
    {
        $barber = Barber::find($barberId);
        $barber->rating = $this->reservationRatingRepository->getBarberRatings($barberId)->avg('barber_rating');
        $barber->rating_count = $this->reservationRatingRepository->getBarberRatings($barberId)->count();
        $barber->save();
    }

    private function updateBarbershopRating($barbershopId)
    {
        $barbershop = Barbershop::find($barbershopId);
        $barbershop->rating = $this->reservationRatingRepository->getBarbershopRatings($barbershopId)->avg('barbershop_rating');
        $barbershop->rating_count = $this->reservationRatingRepository->getBarbershopRatings($barbershopId)->count();
        $barbershop->save();
    }
}
