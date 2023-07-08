<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\BarbershopOwner;
use App\Mail\VerifyEmail;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Repositories\BarbershopOwnerRepository;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;
use App\Repositories\PasswordResetTokenRepository;
use App\Services\BarbershopOwnerEmailVerificationService;
use App\Services\BarbershopOwnerPasswordResetService;

class BarbershopOwnerAuthService
{
    private $barbershopOwnerRepository;
    private $passwordResetTokenRepository;
    private $barbershopOwnerPasswordResetService;
    private $barbershopOwnerEmailVerificationService;

    public function __construct(
        BarbershopOwnerRepository $barbershopOwnerRepository,
        PasswordResetTokenRepository $passwordResetTokenRepository,
        BarbershopOwnerPasswordResetService $barbershopOwnerPasswordResetService,
        BarbershopOwnerEmailVerificationService $barbershopOwnerEmailVerificationService
    ) {
        $this->barbershopOwnerRepository = $barbershopOwnerRepository;
        $this->passwordResetTokenRepository = $passwordResetTokenRepository;
        $this->barbershopOwnerPasswordResetService = $barbershopOwnerPasswordResetService;
        $this->barbershopOwnerEmailVerificationService = $barbershopOwnerEmailVerificationService;
    }

    public function register(array $data)
    {
        $barbershopOwner = $this->barbershopOwnerRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        if ($barbershopOwner) {
            $this->deleteExistingPasswordResetToken($data['email']);
            $pin = rand(100000, 999999);
            $this->storePasswordResetToken($data['email'], $pin);
        }

        Mail::to($data['email'])->send(new VerifyEmail($pin));

        $token = $barbershopOwner->guard(['barbershop-owner-api'])->createToken('BarbershopOwnerAccessToken')->accessToken;
        $response = [
            'barbershopOwner' => $barbershopOwner,
            'token' => $token,
            'message' => 'Barbershop owner registered successfully',
        ];

        return $response;
    }

    public function verifyEmail(array $data)
    {
        return $this->barbershopOwnerEmailVerificationService->verifyEmail($data);
    }

    public function resendPin(array $data)
    {
        return $this->barbershopOwnerEmailVerificationService->resendPin($data);
    }

    public function login(array $data)
    {
        $email = $data['email'];
        $password = $data['password'];

        $barbershopOwner = $this->barbershopOwnerRepository->findByEmail($email);

        if (!$barbershopOwner || !Hash::check($password, $barbershopOwner->password)) {
            return [
                'response' => 'Please enter the correct email or password!',
            ];
        }

        $token = $barbershopOwner->guard(['barbershop-owner-api'])->createToken('BarbershopOwnerAccessToken')->accessToken;
        $response = [
            'barbershopOwner' => $barbershopOwner,
            'token' => $token,
        ];

        return $response;
    }

    public function logout()
    {
        Auth::user()->token()->revoke();

        return [
            'response' => 'Logged out',
        ];
    }

    public function forgotPassword(array $data)
    {
        return $this->barbershopOwnerPasswordResetService->forgotPassword($data);
    }

    public function verifyPin(array $data)
    {
        return $this->barbershopOwnerPasswordResetService->verifyPin($data);
    }

    public function resetPassword(array $data)
    {
        return $this->barbershopOwnerPasswordResetService->resetPassword($data);
    }

    protected function deleteExistingPasswordResetToken($email)
    {
        $this->passwordResetTokenRepository->deleteByEmail($email);
    }

    protected function storePasswordResetToken($email, $token)
    {
        $this->passwordResetTokenRepository->create(
            $email,
            $token,
        );
    }

    protected function deletePasswordResetToken($email, $token)
    {
        $this->passwordResetTokenRepository->deleteByEmailAndToken($email, $token);
    }
}
