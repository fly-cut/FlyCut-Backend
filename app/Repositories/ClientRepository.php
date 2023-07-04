<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Support\Facades\Hash;

class ClientRepository
{
    public function create(array $data): Client
    {
        return Client::create($data);
    }

    public function findByEmail(string $email): ?Client
    {
        return Client::where('email', $email)->first();
    }

    public function findById(int $id): ?Client
    {
        return Client::find($id);
    }

    public function update(Client $client, array $data): bool
    {
        return $client->update($data);
    }

    public function delete(Client $client): bool
    {
        return $client->delete();
    }

    public function existsByEmail(string $email): bool
    {
        return Client::where('email', $email)->exists();
    }

    public function firstOrCreate(array $attributes, array $values = []): Client
    {
        return Client::firstOrCreate($attributes, $values);
    }
    public function updatePasswordByEmail(string $email, string $password): bool
    {
        $client = $this->findByEmail($email);

        if ($client) {
            $client->password = Hash::make($password);
            return $client->save();
        }

        return false;
    }
}
