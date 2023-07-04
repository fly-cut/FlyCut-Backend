<?php

namespace App\Services;

use Carbon\Carbon;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Repositories\ClientRepository;
use App\Repositories\PasswordResetTokenRepository;

class ClientEmailVerificationService
{
    private $clientRepository;
    private $passwordResetTokenRepository;

    public function __construct(
        ClientRepository $clientRepository,
        PasswordResetTokenRepository $passwordResetTokenRepository
    ) {
        $this->clientRepository = $clientRepository;
        $this->passwordResetTokenRepository = $passwordResetTokenRepository;
    }

    public function verifyEmail(array $data)
    {
        $email = Auth::guard('client-api')->user()->email;
        $token = $data['token'];

        $this->deletePasswordResetToken($email, $token);

        $client = $this->clientRepository->findByEmail($email);
        $client->email_verified_at = Carbon::now()->toDateTimeString();
        $client->save();

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
            'message' => 'A verification mail has been resent',
        ];

        return $response;
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
