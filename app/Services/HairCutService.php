<?php

namespace App\Services;

use App\Repositories\ServiceRepository;
use App\Repositories\VariationRepository;

class HairCutService
{
    private $serviceRepository;
    private $variationRepository;

    public function __construct(ServiceRepository $serviceRepository, VariationRepository $variationRepository)
    {
        $this->serviceRepository = $serviceRepository;
        $this->variationRepository = $variationRepository;
    }

    public function getAllHaircuts()
    {
        $service = $this->serviceRepository->getByName('haircut');
        if (!$service) {
            return [
                'success' => false,
                'message' => 'No service found',
            ];
        }
        $haircuts = $this->variationRepository->getByServiceId($service->id);
        if ($haircuts->isEmpty()) {
            return [
                'success' => false,
                'message' => 'No haircuts found',
            ];
        }

        return [
            'success' => true,
            'data' => $haircuts,
        ];
    }

    public function searchByName($name)
    {
        $haircut = $this->variationRepository->searchByName($name);
        if ($haircut->isEmpty()) {
            return [
                'success' => false,
                'message' => 'No haircut found',
            ];
        }

        return [
            'success' => true,
            'data' => $haircut,
        ];
    }
}
