<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Client;
use App\Mail\VerifyEmail;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Repositories\ClientRepository;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;
use App\Repositories\PasswordResetTokenRepository;

class ClientAuthService
{
    private $clientRepository;
    private $passwordResetTokenRepository;
    public function __construct(ClientRepository $clientRepository, PasswordResetTokenRepository $passwordResetTokenRepository)
    {
        $this->clientRepository = $clientRepository;
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

    public function forgotPassword(array $data)
    {
        $email = $data['email'];

        if (!$this->clientRepository->existsByEmail($email)) {
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
            'message' => 'Please check your email for a 6 digit pin',
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
                'message' => 'Token Expired',
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

        $this->clientRepository->updatePasswordByEmail($email, $password);

        $client = $this->clientRepository->findByEmail($email);

        $token = $client->guard(['client-api'])->createToken('ClientAccessToken')->accessToken;

        $response = [
            'success' => true,
            'message' => 'Your password has been reset',
            'token' => $token,
        ];

        return $response;
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
}
