<?php

namespace App\Repositories;

use App\Models\Variation;

class VariationRepository
{
    public function getByServiceId($serviceId)
    {
        return Variation::where('service_id', $serviceId)->get();
    }

    public function searchByName($name)
    {
        return Variation::where('name', 'like', '%' . $name . '%')->get();
    }
}
