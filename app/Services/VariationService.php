<?php

namespace App\Services;

use App\Repositories\VariationRepository;
use Illuminate\Support\Facades\File;

class VariationService
{
    protected $variationRepository;

    public function __construct(VariationRepository $variationRepository)
    {
        $this->variationRepository = $variationRepository;
    }

    public function getAllVariations()
    {
        return $this->variationRepository->getAll();
    }

    public function createVariation($request)
    {
        $path = $request->file('image');
        $filename = $path->getClientOriginalName();
        $destinationPath = public_path() . '/images';
        $path->move($destinationPath, $filename);

        $request['image'] = $filename;

        return $this->variationRepository->create($request);
    }

    public function updateVariation($request, $id)
    {
        $variation = $this->variationRepository->getById($id);

        $image = $request->file('image');
        if ($image) {
            if (File::exists(public_path('images/' . $variation->image))) {
                File::delete(public_path('images/' . $variation->image));
            }

            $path = $request->file('image');
            $filename = $path->getClientOriginalName();
            $destinationPath = public_path() . '/images';
            $path->move($destinationPath, $filename);

            $request['image'] = $filename;
        }

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
