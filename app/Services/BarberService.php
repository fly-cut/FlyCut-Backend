<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Slot;
use App\Models\Barber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Repositories\BarberRepository;
use App\Exceptions\BarberNotFoundException;
use App\Models\Barbershop;

class BarberService
{
    protected $barberRepository;
    protected $slotAvailabilityService;
    public function __construct(BarberRepository $barberRepository, SlotAvailabilityService $slotAvailabilityService)
    {
        $this->barberRepository = $barberRepository;
        $this->slotAvailabilityService = $slotAvailabilityService;
    }

    public function store(array $data)
    {
        $barbershopOwner = Auth::guard('barbershopOwner-api')->user();
        $barbershop = Barbershop::find($data['barbershop_id']);
        if ($barbershop->barbershop_owner_id !== $barbershopOwner->id) {
            throw new \Exception('You are not authorized to store a barber in this barbershop');
        }
        try {
            $image = $data['image'];
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $image_name);

            $barber = $this->barberRepository->create([
                'name' => $data['name'],
                'barbershop_id' => $data['barbershop_id'],
                'image' => $image_name,
            ]);

            return $barber;
        } catch (\Exception $e) {
            throw new \Exception('Failed to store barber: ' . $e->getMessage());
        }
    }

    public function update(array $data, $barberId)
    {
        $barbershopOwner = Auth::guard('barbershopOwner-api')->user();
        $barber = $this->barberRepository->find($barberId);

        if (is_null($barber)) {
            throw new BarberNotFoundException('Barber not found');
        }

        $barbershop = Barbershop::find($data['barbershop_id']);

        if ($barbershop->barbershop_owner_id !== $barbershopOwner->id) {
            throw new \Exception('You are not authorized to update this barber');
        }

        try {
            $barber->name = $data['name'];
            $barber->barbershop_id = $data['barbershop_id'];

            if (isset($data['image'])) {
                $image_path = public_path('images/' . $barber->image);
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }

                $image = $data['image'];
                $image_name = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/images'), $image_name);
                $barber->image = $image_name;
            }

            $this->barberRepository->save($barber);

            return $barber;
        } catch (\Exception $e) {
            throw new \Exception('Failed to update barber: ' . $e->getMessage());
        }
    }

    public function destroy($barberId)
    {
        $barber = $this->barberRepository->find($barberId);

        if (is_null($barber)) {
            throw new BarberNotFoundException('Barber not found');
        }

        $this->barberRepository->delete($barberId);
    }

    public function checkAvailability($startTime, $numberOfSlots, $barberId)
    {
        return $this->slotAvailabilityService->checkAvailability($startTime, $numberOfSlots, $barberId);
    }
}
