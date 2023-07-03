<?php

namespace App\Repositories;

use App\Models\Service;

class ServiceRepository
{
    public function getByName($name)
    {
        return Service::where('name', $name)->first();
    }
}
