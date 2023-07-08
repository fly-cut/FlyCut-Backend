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
        $filename = $this->uploadImage($request->file('image'));

        $variationData = [
            'service_id' => $request->service_id,
            'name' => $request->name,
            'image' => $filename,
        ];

        return $this->variationRepository->create($variationData);
    }

    public function updateVariation($request, $id)
    {
        $variation = $this->variationRepository->getById($id);

        if ($request->hasFile('image')) {
            $this->deleteImage($variation->image);

            $filename = $this->uploadImage($request->file('image'));
            $variation->image = $filename;
        }

        $variation->name = $request->name;
        $variation->service_id = $request->service_id;

        return $this->variationRepository->update($variation, $id);
    }

    public function getVariationById($id)
    {
        return $this->variationRepository->getById($id);
    }

    public function deleteVariation($id)
    {
        $variation = $this->variationRepository->getById($id);

        $this->deleteImage($variation->image);

        $this->variationRepository->delete($id);
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
