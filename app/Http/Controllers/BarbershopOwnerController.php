<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordBarbershopOwnerRequest;
use App\Http\Requests\UpdateProfileBarbershopOwnerRequest;
use Illuminate\Http\Request;
use App\Services\BarbershopOwnerService;


class BarbershopOwnerController extends Controller
{
    protected $barbershopOwnerService;

    public function __construct(BarbershopOwnerService $barbershopOwnerService)
    {
        $this->barbershopOwnerService = $barbershopOwnerService;
    }

    public function changePassword(ChangePasswordBarbershopOwnerRequest $request)
    {
        return $this->barbershopOwnerService->changePassword($request);
    }

    public function updateProfile(UpdateProfileBarbershopOwnerRequest $request)
    {
        return $this->barbershopOwnerService->updateProfile($request);
    }
    public function assignToken(Request $request)
    {
        return $this->barbershopOwnerService->assignToken($request);
    }
}
