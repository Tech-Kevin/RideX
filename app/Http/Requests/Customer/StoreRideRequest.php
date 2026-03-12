<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->isCustomer();
    }

    public function rules(): array
    {
        return [
            'pickup_address' => ['required', 'string', 'max:255'],
            'pickup_lat' => ['required', 'numeric'],
            'pickup_lng' => ['required', 'numeric'],
            'drop_address' => ['required', 'string', 'max:255'],
            'drop_lat' => ['required', 'numeric'],
            'drop_lng' => ['required', 'numeric'],
            'vehicle_type' => ['required', 'string', \Illuminate\Validation\Rule::enum(\App\Enums\VehicleType::class)],
        ];
    }
}
