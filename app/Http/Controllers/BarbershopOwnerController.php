<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordBarbershopOwnerRequest;
use App\Http\Requests\UpdateProfileBarbershopOwnerRequest;
use App\Models\BarbershopOwner;
use Illuminate\Http\Request;
use App\Services\BarbershopOwnerService;
use Illuminate\Support\Facades\Auth;

class BarbershopOwnerController extends Controller
{
    protected $barbershopOwnerService;
    public function index()
    {
        return BarbershopOwner::all();
    }
    public function __construct(BarbershopOwnerService $barbershopOwnerService)
    {
        $this->barbershopOwnerService = $barbershopOwnerService;
    }

    public function changePassword(ChangePasswordBarbershopOwnerRequest $request)
    {
        $user = Auth::user();
        $currentPassword = $request->input('current_password');
        $newPassword = $request->input('new_password');

        return $this->barbershopOwnerService->changePassword($user, $currentPassword, $newPassword);
    }

    public function updateProfile(UpdateProfileBarbershopOwnerRequest $request)
    {
        $user = Auth::user();
        $formData = $request->validated();

        return $this->barbershopOwnerService->updateProfile($user, $formData);
    }

    public function assignToken(Request $request)
    {
        $user = Auth::user(); // Assuming you're using Laravel's authentication system
        $token = $request->input('token');

        return $this->barbershopOwnerService->assignToken($user, $token);
    }
}
