<?php

namespace App\Services;

use App\Models\Admin;
use App\Repositories\AdminRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthService
{
    private $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function register(array $data): array
    {
        $data['password'] = bcrypt($data['password']);
        $admin = $this->adminRepository->create($data);
        $token = $admin->guard(['admin-api'])->createToken('AdminAccessToken')->accessToken;

        return [
            'admin' => $admin,
            'token' => $token,
        ];
    }

    public function login(array $credentials): ?array
    {
        $admin = $this->adminRepository->findByEmail($credentials['email']);

        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            return null;
        }

        $token = $admin->guard(['admin-api'])->createToken('AdminAccessToken')->accessToken;

        return [
            'admin' => $admin,
            'token' => $token,
        ];
    }

    public function logout()
    {
        Auth::user()->token()->revoke();
    }
}
