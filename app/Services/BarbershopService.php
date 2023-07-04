<?php

namespace App\Services;

use App\Repositories\BarbershopRepository;

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
        return $this->barbershopRepository->create($data);
    }

    public function updateBarbershop($id, $data)
    {
        return $this->barbershopRepository->update($id, $data);
    }

    public function getBarbershopById($id)
    {
        return $this->barbershopRepository->getById($id);
    }

    public function deleteBarbershop($id)
    {
        $this->barbershopRepository->delete($id);
    }

    public function getBarbershopByOwnerId($ownerId)
    {
        return $this->barbershopRepository->getByOwnerId($ownerId);
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

    public function getBarbershopServicesWithPriceAndSlots($barbershop_id)
    {
        return $this->barbershopRepository->getBarbershopServicesWithPriceAndSlots($barbershop_id);
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
        return $this->barbershopRepository->getReservations($request);
    }
}
