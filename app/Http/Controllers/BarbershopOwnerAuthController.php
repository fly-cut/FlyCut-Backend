<?php

namespace App\Http\Controllers;

use App\Models\BarbershopOwner;
use App\Http\Requests\ResendPinRequest;
use App\Http\Requests\VerifyPinRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Services\BarbershopOwnerAuthService;
use App\Http\Requests\BarbershopOwnerLoginRequest;
use App\Http\Requests\RegisterBarbershopOwnerRequest;

class BarbershopOwnerAuthController extends Controller
{
    private $barbershopOwnerAuthService;

    public function __construct(BarbershopOwnerAuthService $barbershopOwnerAuthService)
    {
        $this->barbershopOwnerAuthService = $barbershopOwnerAuthService;
    }

    public function register(RegisterBarbershopOwnerRequest $request)
    {
        $response = $this->barbershopOwnerAuthService->register($request->validated());
        return response($response, 201);
    }

    public function verifyEmail(VerifyEmailRequest $request)
    {
        $response = $this->barbershopOwnerAuthService->verifyEmail($request->validated());
        return response()->json($response);
    }

    public function resendPin(ResendPinRequest $request)
    {
        $response = $this->barbershopOwnerAuthService->resendPin($request->validated());
        return response()->json($response);
    }

    public function login(BarbershopOwnerLoginRequest $request)
    {
        $response = $this->barbershopOwnerAuthService->login($request->validated());
        return response()->json($response);
    }

    public function logout()
    {
        $response = $this->barbershopOwnerAuthService->logout();
        return response()->json($response);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $response = $this->barbershopOwnerAuthService->forgotPassword($request->validated());
        return response()->json($response);
    }

    public function verifyPin(VerifyPinRequest $request)
    {
        $response = $this->barbershopOwnerAuthService->verifyPin($request->validated());
        return response()->json($response);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $response = $this->barbershopOwnerAuthService->resetPassword($request->validated());
        return response()->json($response);
    }
}
