<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Enums\RideStatus;

class UpdateRideStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->isDriver();
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(RideStatus::class)],
        ];
    }
}
