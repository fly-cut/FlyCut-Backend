<?php

namespace App\Repositories;

use App\Models\BarbershopOwner;
use Illuminate\Support\Facades\Hash;

class BarbershopOwnerRepository
{
    public function create(array $data): BarbershopOwner
    {
        return BarbershopOwner::create($data);
    }

    public function findByEmail(string $email): ?BarbershopOwner
    {
        return BarbershopOwner::where('email', $email)->first();
    }

    public function findById(int $id): ?BarbershopOwner
    {
        return BarbershopOwner::find($id);
    }

    public function update(BarbershopOwner $barbershopOwner, array $data): bool
    {
        return $barbershopOwner->update($data);
    }

    public function delete(BarbershopOwner $barbershopOwner): bool
    {
        return $barbershopOwner->delete();
    }

    public function existsByEmail(string $email)
    {
        return BarbershopOwner::where('email', $email)->first();
    }

    public function firstOrCreate(array $attributes, array $values = []): BarbershopOwner
    {
        return BarbershopOwner::firstOrCreate($attributes, $values);
    }

    public function updatePasswordByEmail(string $email, string $password): bool
    {
        $barbershopOwner = $this->findByEmail($email);

        if ($barbershopOwner) {
            $barbershopOwner->password = Hash::make($password);
            return $barbershopOwner->save();
        }

        return false;
    }
}
