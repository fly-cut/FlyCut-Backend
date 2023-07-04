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
        $path = $data->file('image');
        $filename = $path->getClientOriginalName();
        $destinationPath = public_path().'/images';
        $path->move($destinationPath, $filename);

        $service = Service::create([
            'name' => $data->name,
            'image' => $filename,
        ]);
        return $service;
    }

    public function update($data, $id)
    {
        $service = Service::find($id);
        $image = $data->file('image');
        if ($image) {
            if (File::exists(public_path('images/'.$service->image))) {
                File::delete(public_path('images/'.$service->image));
                $path = $data->file('image');
                $filename = $path->getClientOriginalName();
                $destinationPath = public_path().'/images';
                $path->move($destinationPath, $filename);
                $service->image = $filename;
            }
        }
        $service->name = $data->name;
        $service->save();
        return $service;
    }

    public function delete($id)
    {
        $service = Service::find($id);
        if (File::exists(public_path('images/'.$service->image))) {
            File::delete(public_path('images/'.$service->image));
        }
        Service::destroy($id);
    }

}
