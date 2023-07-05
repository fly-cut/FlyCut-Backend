<?php

namespace App\Services;

use App\Repositories\BarbershopOwnerRepository;
use Illuminate\Support\Facades\Hash;

class BarbershopOwnerService
{
    private $barbershopOwnerRepository;

    public function __construct(BarbershopOwnerRepository $barbershopOwnerRepository)
    {
        $this->barbershopOwnerRepository = $barbershopOwnerRepository;
    }

    public function changePassword($user, $current_password, $new_password)
    {
        if (!Hash::check($current_password, $user->password)) {
            $message = [
                'message' => 'Password isn\'t correct',
            ];

            return response($message, 422);
        }

        $this->barbershopOwnerRepository->updatePasswordByEmail($user->email, $new_password);

        $message = [
            'message' => 'Password changed successfully',
        ];

        return response($message, 200);
    }

    public function updateProfile($user, $formData)
    {
        if (isset($formData['image'])) {
            $image = $formData['image'];
            $image_name = time() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('images/'), $image_name);
            $formData['image'] = $image_name;
        }

        $this->barbershopOwnerRepository->update($user, $formData);

        $message = [
            'message' => 'Profile updated successfully',
            'barbershopOwner' => $user,
        ];

        return response($message, 200);
    }

    public function assignToken($owner, $token)
    {
        $owner->token = $token;
        $owner->save();

        $message = [
            'message' => 'Token assigned successfully',
        ];

        return response($message, 200);
    }
}
