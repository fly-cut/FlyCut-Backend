<?php

namespace App\Services;

namespace App\Services;

use Carbon\Carbon;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Repositories\ClientRepository;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;
use App\Services\ClientPasswordResetService;
use App\Services\ClientEmailVerificationService;
use App\Repositories\PasswordResetTokenRepository;

class ClientAuthService
{
    private $clientRepository;
    private $clientEmailVerificationService;
    private $clientPasswordResetService;
    private $passwordResetTokenRepository;
    public function __construct(
        ClientRepository $clientRepository,
        ClientEmailVerificationService $clientEmailVerificationService,
        ClientPasswordResetService $clientPasswordResetService,
        PasswordResetTokenRepository $passwordResetTokenRepository
    ) {
        $this->clientRepository = $clientRepository;
        $this->clientEmailVerificationService = $clientEmailVerificationService;
        $this->clientPasswordResetService = $clientPasswordResetService;
        $this->passwordResetTokenRepository = $passwordResetTokenRepository;
    }

    public function register(array $data)
    {
        $client = $this->clientRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        if ($client) {
            $this->deleteExistingPasswordResetToken($data['email']);
            $pin = rand(100000, 999999);
            $this->storePasswordResetToken($data['email'], $pin);
        }

        Mail::to($data['email'])->send(new VerifyEmail($pin));

        $token = $client->guard(['client-api'])->createToken('ClientAccessToken')->accessToken;
        $response = [
            'client' => $client,
            'token' => $token,
            'message' => 'Client registered successfully',
        ];

        return $response;
    }


    public function login(array $data)
    {
        $email = $data['email'];
        $password = $data['password'];

        $client = $this->clientRepository->findByEmail($email);

        if (!$client || !Hash::check($password, $client->password)) {
            return [
                'response' => 'Please enter the right email or password!',
            ];
        }

        $token = $client->guard(['client-api'])->createToken('ClientAccessToken')->accessToken;
        $response = [
            'client' => $client,
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

    public function redirectToProvider($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }
        try {
            $client = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $exception) {
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }

        $clientCreated = $this->clientRepository->firstOrCreate([
            'email' => $client->getEmail(),
        ], [
            'email_verified_at' => now(),
            'name' => $client->getName(),
            'status' => true,
        ]);

        $clientCreated->client_providers()->updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $client->getId(),
            ],
            [
                'avatar' => $client->getAvatar(),
            ]
        );

        $token = $clientCreated->guard(['client-api'])->createToken('ClientAccessToken')->accessToken;

        return $clientCreated->toArray() + ['token' => $token];
    }



    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['facebook', 'twitter', 'google'])) {
            return response()->json(['error' => 'Please login using facebook, twitter or google'], 422);
        }
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

    public function forgotPassword(array $data)
    {
        return $this->clientPasswordResetService->forgotPassword($data);
    }

    public function verifyPin(array $data)
    {
        return $this->clientPasswordResetService->verifyPin($data);
    }

    public function resetPassword(array $data)
    {
        return $this->clientPasswordResetService->resetPassword($data);
    }

    public function verifyEmail(array $data)
    {
        return $this->clientEmailVerificationService->verifyEmail($data);
    }

    public function resendPin(array $data)
    {
        return $this->clientEmailVerificationService->resendPin($data);
    }
}
