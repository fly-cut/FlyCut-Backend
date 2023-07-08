<?php

namespace App\Repositories;

use App\Models\Service;
use Illuminate\Support\Facades\File;

class ServiceRepository
{
    public function getAll()
    {
        return Service::all();
    }

    public function getById($id)
    {
        return Service::find($id);
    }

    public function create($data)
    {
        return Service::create($data);
    }

    public function update($data, $id)
    {
        $service = Service::find($id);
        $service->update($data);
        return $service;
    }

    public function delete($id)
    {
        $service = Service::find($id);
        if (File::exists(public_path('images/' . $service->image))) {
            File::delete(public_path('images/' . $service->image));
        }
        Service::destroy($id);
    }

    public function getByName($name)
    {
        return Service::where('name', $name)->first();
    }
}
