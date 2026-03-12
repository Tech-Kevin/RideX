<?php

namespace App\Http\Controllers;

use App\Enums\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'dob' => ['nullable', 'date'],
            'avatar' => ['nullable', 'image', 'max:5120'], // 5MB max
        ];

        if ($user->isDriver()) {
            $rules['vehicle_type'] = ['required', Rule::enum(VehicleType::class)];
            $rules['vehicle_number'] = ['required', 'string', 'max:20'];
            $rules['licence_number'] = ['required', 'string', 'max:50'];
            $rules['rc_book'] = ['nullable', 'image', 'max:5120'];
            $rules['vehicle_photo'] = ['nullable', 'image', 'max:5120'];
            $rules['driving_licence'] = ['nullable', 'image', 'max:5120'];
        }

        $validated = $request->validate($rules);

        $user->update([
            'name' => $validated['name'],
            'dob' => $validated['dob'],
            'vehicle_type' => $validated['vehicle_type'] ?? $user->vehicle_type,
            'vehicle_number' => $validated['vehicle_number'] ?? $user->vehicle_number,
            'licence_number' => $validated['licence_number'] ?? $user->licence_number,
            'verification_status' => 'pending',
            'rejection_note' => null,
        ]);

        // Handle Avatar
        if ($request->hasFile('avatar')) {
            $user->clearMediaCollection('avatar');
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        // Handle Driver Documents
        if ($user->isDriver()) {
            if ($request->hasFile('rc_book')) {
                $user->clearMediaCollection('rc_book');
                $user->addMediaFromRequest('rc_book')->toMediaCollection('rc_book');
            }
            if ($request->hasFile('vehicle_photo')) {
                $user->clearMediaCollection('vehicle_photo');
                $user->addMediaFromRequest('vehicle_photo')->toMediaCollection('vehicle_photo');
            }
            if ($request->hasFile('driving_licence')) {
                $user->clearMediaCollection('driving_licence');
                $user->addMediaFromRequest('driving_licence')->toMediaCollection('driving_licence');
            }
        }

        return back()->with('success', 'Profile updated successfully.');
    }
}
