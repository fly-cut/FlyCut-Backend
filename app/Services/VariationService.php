<?php

namespace App\Services;

use App\Repositories\VariationRepository;

class VariationService
{
    protected $variationRepository;

    public function __construct(VariationRepository $serviceRepository)
    {
        $this->variationRepository = $serviceRepository;
    }

    public function getAllVariations()
    {
        return $this->variationRepository->getAll();
    }

    public function createVariation($request)
    {
        return $this->variationRepository->create($request);
    }

    public function updateVariation($request, $id)
    {
        return $this->variationRepository->update($request, $id);
    }

    public function getVariationById($id)
    {
        return $this->variationRepository->getById($id);
    }

    public function deleteVariation($id)
    {
        $this->variationRepository->delete($id);
    }
}
