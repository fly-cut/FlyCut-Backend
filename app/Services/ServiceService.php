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
        $filename = $this->uploadImage($data->file('image'));

        $serviceData = [
            'name' => $data->name,
            'image' => $filename,
        ];

        return $this->serviceRepository->create($serviceData);
    }

    public function updateService($data, $id)
    {
        $service = $this->serviceRepository->getById($id);

        if ($data->hasFile('image')) {
            $this->deleteImage($service->image);

            $filename = $this->uploadImage($data->file('image'));
            $service->image = $filename;
        }

        $service->name = $data->name;

        return $this->serviceRepository->update($service);
    }

    public function getServiceById($id)
    {
        return $this->serviceRepository->getById($id);
    }

    public function deleteService($id)
    {
        $service = $this->serviceRepository->getById($id);

        $this->deleteImage($service->image);

        $this->serviceRepository->delete($id);
    }

    protected function uploadImage($file)
    {
        $filename = $file->getClientOriginalName();
        $destinationPath = public_path() . '/images';
        $file->move($destinationPath, $filename);

        return $filename;
    }

    protected function deleteImage($filename)
    {
        $imagePath = public_path('images/' . $filename);

        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
    }
}
