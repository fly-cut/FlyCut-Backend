<?php

namespace App\Repositories;

use App\Models\Barber;
use App\Models\Client;
use App\Models\Service;
use App\Models\Variation;
use App\Models\Barbershop;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\ReservationController;

class BarbershopRepository
{
    public function getAll()
    {
        return Barbershop::all();
    }

    public function getById($id)
    {
        return Barbershop::find($id);
    }

    public function create($data)
    {
        $barbershop = new Barbershop;
        $barbershop->name = $data['name'];
        $barbershop->description = $data['description'];
        $barbershop->address = $data['address'];
        $barbershop->longitude = $data['longitude'];
        $barbershop->latitude = $data['latitude'];
        $barbershop->city = $data['city'];
        $barbershop->barbershop_owner_id = Auth::id();

        $barbershop->save();

        return $barbershop;
    }

    public function update($id, $data)
    {
        $barbershop = Barbershop::find($id);
        $barbershop->name = $data->name;
        $barbershop->description = $data->description;
        $barbershop->address = $data->address;
        $barbershop->city = $data->city;
        if ($data->longitude != null && $data->latitude != null) {
            $barbershop->longitude = $data->longitude;
            $barbershop->latitude = $data->latitude;
        }

        $barbershop->update();

        return $barbershop;
    }

    public function delete($id)
    {
        Barbershop::destroy($id);
    }

    public function getByOwnerId($ownerId)
    {
        return Barbershop::where('barbershop_owner_id', $ownerId)->get();
    }

    public function getBarbershopServices($barbershop_id)
    {
        $barbershop = $this->getById($barbershop_id);
        $services = $barbershop->services()->withPivot('price', 'slots')->get();
        return $services;
    }
    public function addServices($request)
    {
        $barbershop = $this->getByOwnerId(Auth::id())->first();
        $services = $request->services;
        $barbershop->services()->attach($services);
    }

    public function removeServices($request)
    {
        $barbershop = $this->getByOwnerId(Auth::id())->first();
        $services = $request->services;
        $barbershop->services()->detach($services);
    }

    public function editServicePriceAndSlots($request)
    {
        $barbershop = $this->getByOwnerId(Auth::id())->first();
        $services = $request->services;
        foreach ($services as $service) {
            $barbershop->services()->updateExistingPivot($service['id'], ['price' => $service['price'], 'slots' => $service['slots']]);
        }
    }

    public function search($request)
    {
        $searchQuery = $request->get('searchQuery');
        $userLongitude = $request->get('userLongitude');
        $userLatitude = $request->get('userLatitude');
        $barbershops = Barbershop::query()
            ->where(function ($query) use ($searchQuery) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchQuery) . '%'])
                    ->orWhereRaw('LOWER(city) LIKE ?', ['%' . strtolower($searchQuery) . '%'])
                    ->orWhereRaw('LOWER(address) LIKE ?', ['%' . strtolower($searchQuery) . '%']);
            })
            ->orderByRaw(
                "ABS(latitude - $userLatitude) + ABS(longitude - $userLongitude)"
            )
            ->get();
        return $barbershops;
    }

    public function getNearbyBarbershops($request)
    {
        $userLongitude = $request->get('userLongitude');
        $userLatitude = $request->get('userLatitude');
        $barbershops = Barbershop::orderByRaw(
            "ABS(latitude - $userLatitude) + ABS(longitude - $userLongitude)"
        )
            ->limit(5)
            ->get();
        return $barbershops;
    }

    public function getBarbers($barbershop_id)
    {
        $barbershop = $this->getById($barbershop_id);
        $barbers = $barbershop->barbers;
        return $barbers;
    }
}
