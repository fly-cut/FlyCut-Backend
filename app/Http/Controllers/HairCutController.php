<?php

namespace App\Http\Controllers;

use App\Http\Requests\HairCutSearchRequest;
use App\Http\Requests\SearchHairCutRequest;
use App\Services\HairCutService;

class HairCutController extends Controller
{
    private $hairCutService;

    public function __construct(HairCutService $hairCutService)
    {
        $this->hairCutService = $hairCutService;
    }

    public function getAllHaircuts()
    {
        $haircuts = $this->hairCutService->getAllHaircuts();

        return response()->json($haircuts);
    }

    public function search(SearchHairCutRequest $request)
    {
        $name = $request->input('name');
        $haircut = $this->hairCutService->searchByName($name);

        return response()->json($haircut);
    }
}
