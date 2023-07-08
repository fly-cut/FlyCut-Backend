<?php

namespace App\Services;

use App\Models\Barber;
use App\Models\Client;
use App\Models\Service;
use App\Models\Variation;
use App\Models\Barbershop;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Repositories\BarbershopRepository;
use App\Http\Controllers\ReservationController;
use App\Models\BarbershopOwner;

class BarbershopService
{
    protected $barbershopRepository;

    public function __construct(BarbershopRepository $barbershopRepository)
    {
        $this->barbershopRepository = $barbershopRepository;
    }

    public function getAllBarbershops()
    {
        return $this->barbershopRepository->getAll();
    }

    public function createBarbershop($data)
    {
        $barbershop = $this->barbershopRepository->create($data);

        if ($data->hasFile('image')) {
            $path = $data->file('image');
            $filename = $path->getClientOriginalName();
            $destinationPath = public_path() . '/images';
            $path->move($destinationPath, $filename);
            $barbershop->image = $filename;
            $barbershop->save();
        }
        $user = BarbershopOwner::find(Auth::user()->id);
        $user->has_barbershop = true;
        $user->save();
        $barbershop->barbershop_owner = Auth::user();
        return $barbershop;
    }

    public function updateBarbershop($id, $data)
    {
        $barbershop = $this->barbershopRepository->getById($id);

        $barbershop = $this->barbershopRepository->update($id, $data);

        if ($data->hasFile('image')) {
            if (File::exists(public_path('images/' . $barbershop->image))) {
                File::delete(public_path('images/' . $barbershop->image));
            }

            $path = $data->file('image');
            $filename = $path->getClientOriginalName();
            $destinationPath = public_path() . '/images';
            $path->move($destinationPath, $filename);
            $barbershop->image = $filename;
            $barbershop->save();
        }

        return $barbershop;
    }

    public function deleteBarbershop($id)
    {
        $this->barbershopRepository->delete($id);
    }

    public function getBarbershopByOwnerId($ownerId)
    {
        return $this->barbershopRepository->getByOwnerId($ownerId);
    }

    public function getBarbershopServices($barbershop_id)
    {
        return $this->barbershopRepository->getBarbershopServices($barbershop_id);
    }
    public function addServicesToBarbershop($request)
    {
        return $this->barbershopRepository->addServices($request);
    }

    public function removeServicesFromBarbershop($request)
    {
        return $this->barbershopRepository->removeServices($request);
    }

    public function editServicePriceAndSlots($request)
    {
        return $this->barbershopRepository->editServicePriceAndSlots($request);
    }

    public function search($request)
    {
        return $this->barbershopRepository->search($request);
    }

    public function getNearbyBarbershops($request)
    {
        return $this->barbershopRepository->getNearbyBarbershops($request);
    }

    public function getReservations($request)
    {
        $barbershop_id = Barbershop::where('barbershop_owner_id', Auth::user()->id)->first()->id;

        $reservations = Reservation::where('barbershop_id', $barbershop_id)->orderBy('date', 'asc')->get();

        $data = [];

        foreach ($reservations as $reservation) {
            ReservationController::getStatus($reservation);
            $reservationId = $reservation->id;
            $services = Service::whereHas('reservations', function ($query) use ($reservationId) {
                $query->where('reservation_id', $reservationId);
            })->get();

            $servicedata = [];

            foreach ($services as $service) {
                $variations = Variation::where('service_id', $service->id)->get();
                $variationData = $variations->toArray();

                $serviceData = $service->toArray();
                $serviceData['variation'] = $variationData[0] ?? null;

                $servicedata[] = $serviceData;
            }
            $client = Client::where('id', $reservation->user_id)->first();
            $barber_name = Barber::where('id', $reservation->barber_id)->first()->name;

            $element = [
                'reservation' => $reservation,
                'services' => $servicedata,
                'client' => $client,
                'barber_name' => $barber_name,
            ];

            $data[] = $element;
        }

        return $data;
    }

    public function getBarbershop($id)
    {
        return $this->barbershopRepository->getById($id);
    }
    public function getBarbers($barbershop_id)
    {
        return $this->barbershopRepository->getBarbers($barbershop_id);
    }
}
