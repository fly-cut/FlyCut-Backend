<?php

namespace App\Repositories;

use App\Models\Variation;
use Illuminate\Support\Facades\File;

class VariationRepository
{
    public function getAll()
    {
        return Variation::all();
    }

    public function getById($id)
    {
        return Variation::find($id);
    }

    public function create($data)
    {
        return Variation::create($data);
    }

    public function update($data, $id)
    {
        $variation = Variation::find($id);
        $variation->update($data);
        return $variation;
    }

    public function delete($id)
    {
        $variation = Variation::find($id);
        if (File::exists(public_path('images/' . $variation->image))) {
            File::delete(public_path('images/' . $variation->image));
        }
        Variation::destroy($id);
    }

    public function getByServiceId($serviceId)
    {
        return Variation::where('service_id', $serviceId)->get();
    }

    public function searchByName($name)
    {
        $haircut = Variation::where('name', 'like', '%' . $name . '%')
            ->orderByRaw("name COLLATE utf8_general_ci ASC")
            ->get();
        return $haircut;
    }
}
