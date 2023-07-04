<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVariationRequest;
use App\Models\Variation;
use App\Services\VariationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class VariationController extends Controller
{
    protected $variationService;

    public function __construct(VariationService $variationService)
    {
        $this->variationService = $variationService;
    }

    public function index()
    {
        $variations = $this->variationService->getAllVariations();

        return response()->json(['data' => $variations]);
    }

    public function show($id)
    {
        $variation = $this->variationService->getVariationById($id);
        if (! $variation) {
            return response()->json(['error' => 'Variation not found'], 404);
        }

        return response()->json(['data' => $variation]);
    }

    public function store(StoreVariationRequest $request)
    {
        $variation = $this->variationService->createVariation($request);
        return response()->json(['variation' => $variation], 201);
    }

    public function update(Request $request, $id)
    {
        $variation = $this->variationService->getVariationById($id);
        if (! $variation) {
            return response()->json(['error' => 'Variation not found'], 404);
        }

        $variation = $this->variationService->updateVariation($request, $id);
        return response()->json(['data' => $variation], 200);
    }

    public function destroy($id)
    {
        $variation = $this->variationService->getVariationById($id);

        if (!$variation) {
            return response()->json(['error' => 'Variation not found'], 404);
        }
        $this->variationService->deleteVariation($id);

        return response()->json(['message' => 'Variation deleted']);
    }
}
