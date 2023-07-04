<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRegisterRequest;
use App\Http\Requests\AdminLoginRequest;
use App\Models\Admin;
use App\Services\AdminAuthService;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    private $adminAuthService;

    public function __construct(AdminAuthService $adminAuthService)
    {
        $this->adminAuthService = $adminAuthService;
    }

    public function register(AdminRegisterRequest $request)
    {
        $response = $this->adminAuthService->register($request->validated());
        return response()->json($response, 201);
    }

    public function login(AdminLoginRequest $request)
    {
        $response = $this->adminAuthService->login($request->validated());

        if (!$response) {
            return response()->json([
                'response' => 'Please enter the right email or password!',
            ], 401);
        }

        return response()->json($response, 200);
    }

    public function logout()
    {
        $this->adminAuthService->logout();

        return response()->json(['message' => 'Successfully logged out.'], 200);
    }
}
