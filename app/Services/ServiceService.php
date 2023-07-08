<?php

namespace App\Services;

use App\Repositories\ServiceRepository;
use Illuminate\Support\Facades\File;

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
        $path = $data->file('image');
        $filename = $path->getClientOriginalName();
        $destinationPath = public_path() . '/images';
        $path->move($destinationPath, $filename);

        $data->image = $filename;
        return $data;
        return $this->serviceRepository->create($data);
    }

    public function updateService($data, $id)
    {
        $service = $this->serviceRepository->getById($id);

        $image = $data->file('image');
        if ($image) {
            if (File::exists(public_path('images/' . $service->image))) {
                File::delete(public_path('images/' . $service->image));
            }

            $path = $data->file('image');
            $filename = $path->getClientOriginalName();
            $destinationPath = public_path() . '/images';
            $path->move($destinationPath, $filename);

            $data['image'] = $filename;
        }

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
