<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientLoginRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterClientRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ResendPinRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Requests\VerifyPinRequest;
use App\Services\ClientAuthService;

class ClientAuthController extends Controller
{
    private $clientAuthService;

    public function __construct(ClientAuthService $clientAuthService)
    {
        $this->clientAuthService = $clientAuthService;
    }

    public function register(RegisterClientRequest $request)
    {
        $response = $this->clientAuthService->register($request->validated());
        return response($response, 201);
    }

    public function verifyEmail(VerifyEmailRequest $request)
    {
        $response = $this->clientAuthService->verifyEmail($request->validated());
        return response()->json($response);
    }

    public function resendPin(ResendPinRequest $request)
    {
        $response = $this->clientAuthService->resendPin($request->validated());
        return response()->json($response);
    }

    public function login(ClientLoginRequest $request)
    {
        $response = $this->clientAuthService->login($request->validated());
        return response()->json($response);
    }

    public function logout()
    {
        $response = $this->clientAuthService->logout();
        return response()->json($response);
    }

    public function redirectToProvider($provider)
    {
        $response = $this->clientAuthService->redirectToProvider($provider);
        return $response;
    }

    public function handleProviderCallback($provider)
    {
        $response = $this->clientAuthService->handleProviderCallback($provider);
        return response()->json($response);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $response = $this->clientAuthService->forgotPassword($request->validated());
        return response()->json($response);
    }

    public function verifyPin(VerifyPinRequest $request)
    {
        $response = $this->clientAuthService->verifyPin($request->validated());
        return response()->json($response);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $response = $this->clientAuthService->resetPassword($request->validated());
        return response()->json($response);
    }
}