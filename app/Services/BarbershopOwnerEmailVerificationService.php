<?php

namespace App\Services;

use Carbon\Carbon;
use App\Mail\VerifyEmail;
use App\Models\BarbershopOwner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Repositories\BarbershopOwnerRepository;
use App\Repositories\PasswordResetTokenRepository;

class BarbershopOwnerEmailVerificationService
{
    private $barbershopOwnerRepository;
    private $passwordResetTokenRepository;
    public function __construct(BarbershopOwnerRepository $barbershopOwnerRepository, PasswordResetTokenRepository $passwordResetTokenRepository)
    {
        $this->passwordResetTokenRepository = $passwordResetTokenRepository;
        $this->barbershopOwnerRepository = $barbershopOwnerRepository;
    }

    public function verifyEmail(array $data)
    {
        $email = Auth::guard('barbershop-owner-api')->user()->email;
        $token = $data['token'];

        $this->deletePasswordResetToken($email, $token);

        $barbershopOwner = $this->barbershopOwnerRepository->findByEmail($email);
        $barbershopOwner->email_verified_at = Carbon::now()->toDateTimeString();
        $barbershopOwner->save();

        $response = [
            'success' => true,
            'message' => 'Email is verified',
        ];

        return $response;
    }

    public function resendPin(array $data)
    {
        $email = $data['email'];

        $this->deleteExistingPasswordResetToken($email);

        $token = random_int(100000, 999999);
        $this->storePasswordResetToken($email, $token);

        Mail::to($email)->send(new VerifyEmail($token));

        $response = [
            'success' => true,
            'message' => 'A verification email has been resent',
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
