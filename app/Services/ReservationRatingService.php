<?php

namespace App\Services;

use App\Repositories\ReservationRatingRepository;


class ReservationRatingService
{
    protected $reservationRatingRepository;

    public function __construct(ReservationRatingRepository $reservationRatingRepository)
    {
        $this->reservationRatingRepository = $reservationRatingRepository;
    }

    public function createReservationRating($request)
    {
        return $this->reservationRatingRepository->create($request);
    }

    public function updateReservationRating($request, $id)
    {
        return $this->reservationRatingRepository->update($request, $id);
    }

    public function getReservationRatingById($id)
    {
        return $this->reservationRatingRepository->getById($id);
    }

    public function deleteReservationRating($id)
    {
        $this->reservationRatingRepository->delete($id);
    }

    public function getBarberRatings($id)
    {
        return $this->reservationRatingRepository->getBarberRatings($id);
    }

    public function getBarbershopRatings($id)
    {
        return $this->reservationRatingRepository->getBarbershopRatings($id);
    }

    public function getBarberRatingByReservationId($id)
    {
        return $this->reservationRatingRepository->getBarberRatingByReservationId($id);
    }




}