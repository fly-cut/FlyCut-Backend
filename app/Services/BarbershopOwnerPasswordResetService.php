<?php

namespace App\Services;

use Carbon\Carbon;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Mail;
use App\Repositories\BarbershopOwnerRepository;
use App\Repositories\PasswordResetTokenRepository;

class BarbershopOwnerPasswordResetService
{
    private $barbershopOwnerRepository;
    private $passwordResetTokenRepository;

    public function __construct(
        BarbershopOwnerRepository $barbershopOwnerRepository,
        PasswordResetTokenRepository $passwordResetTokenRepository
    ) {
        $this->barbershopOwnerRepository = $barbershopOwnerRepository;
        $this->passwordResetTokenRepository = $passwordResetTokenRepository;
    }

    public function forgotPassword(array $data)
    {
        $email = $data['email'];

        if (!$this->barbershopOwnerRepository->existsByEmail($email)) {
            return [
                'success' => false,
                'message' => 'This email does not exist',
            ];
        }

        $this->deleteExistingPasswordResetToken($email);

        $token = random_int(100000, 999999);
        $this->storePasswordResetToken($email, $token);

        Mail::to($email)->send(new ResetPassword($token));

        $response = [
            'success' => true,
            'message' => 'Please check your email for a 6-digit PIN',
        ];

        return $response;
    }

    public function verifyPin(array $data)
    {
        $email = $data['email'];
        $token = $data['token'];

        $check = $this->passwordResetTokenRepository->exists($email, $token);

        if (!$check) {
            return [
                'success' => false,
                'message' => 'Invalid token',
            ];
        }

        $difference = Carbon::now()->diffInSeconds($check->first()->created_at);
        if ($difference > 3600) {
            return [
                'success' => false,
                'message' => 'Token expired',
            ];
        }

        $this->deletePasswordResetToken($email, $token);

        $response = [
            'success' => true,
            'message' => 'You can now reset your password',
        ];

        return $response;
    }

    public function resetPassword(array $data)
    {
        $email = $data['email'];
        $password = $data['password'];

        $this->barbershopOwnerRepository->updatePasswordByEmail($email, $password);

        $barbershopOwner = $this->barbershopOwnerRepository->findByEmail($email);

        $token = $barbershopOwner->guard(['barbershop-owner-api'])->createToken('BarbershopOwnerAccessToken')->accessToken;

        $response = [
            'success' => true,
            'message' => 'Your password has been reset',
            'token' => $token,
        ];

        return $response;
    }

    protected function deleteExistingPasswordResetToken($email)
    {
        $this->passwordResetTokenRepository->deleteByEmail($email);
    }

    protected function storePasswordResetToken($email, $token)
    {
        $this->passwordResetTokenRepository->create($email, $token);
    }

    protected function deletePasswordResetToken($email, $token)
    {
        $this->passwordResetTokenRepository->deleteByEmailAndToken($email, $token);
    }
}
