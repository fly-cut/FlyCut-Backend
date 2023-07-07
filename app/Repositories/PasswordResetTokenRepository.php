<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class PasswordResetTokenRepository
{
    public function deleteByEmail(string $email): int
    {
        return DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete();
    }

    public function deleteByEmailAndToken(string $email, string $token)
    {
        return DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token);
    }

    public function create(string $email, string $token): bool
    {
        return DB::table('password_reset_tokens')
            ->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => now()->toDateTimeString(),
            ]);
    }

    public function exists(string $email, string $token): bool
    {
        return DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->exists();
    }
}
