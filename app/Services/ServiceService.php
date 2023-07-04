<?php

namespace App\Services;

use App\Repositories\ServiceRepository;

class ServiceService
{
    protected $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function getAllServices()
    {
        return $this->serviceRepository->getAll();
    }

    public function createService($data)
    {
        return $this->serviceRepository->create($data);
    }

    public function updateService($data, $id)
    {
        return $this->serviceRepository->update($data, $id);
    }

    public function getServiceById($id)
    {
        return $this->serviceRepository->getById($id);
    }

    public function deleteService($id)
    {
        $this->serviceRepository->delete($id);
    }
}
