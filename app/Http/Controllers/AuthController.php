<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\SendOtpRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Services\AuthService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => ['required', Password::defaults()],
            'role' => 'required|in:customer,driver',
        ]);

        $phone = formatPhone($validated['phone']);

        $existingUser = \App\Models\User::where('phone', $phone)->first();

        // If the user already exists and has a password, they should log in instead
        if ($existingUser && $existingUser->password) {
            return back()->withInput()->with('error', 'Phone number is already registered. Please log in.');
        }

        if ($existingUser) {
            // Check if user is blocked before allowing them to "register" an existing phone number
            if (!$existingUser->is_active) {
                return back()->withInput()->with('error', 'Blocked by admin. Contact admin.');
            }

            // Update the shell user that might have been created by sendOtp
            $existingUser->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'is_phone_verified' => true,
            ]);
            $user = $existingUser;
        } else {
            $user = \App\Models\User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $phone,
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'is_phone_verified' => true, // Auto verify on register for this prototype flow
                'is_active' => true,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);
        session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Account created and logged in successfully.');
    }

    public function showPhoneForm(): View
    {
        return view('auth.phone');
    }

    public function sendOtp(SendOtpRequest $request, AuthService $authService): RedirectResponse
    {
        $phone = $authService->sendOtp($request->validated('phone'));

        session([
            'otp_phone' => $phone,
        ]);

        return redirect()
            ->route('auth.verify.form')
            ->with('success', 'OTP sent successfully.');
    }

    public function showVerifyForm(): View|RedirectResponse
    {
        if (!session()->has('otp_phone')) {
            return redirect()
                ->route('auth.phone.form')
                ->with('error', 'Please enter phone number first.');
        }

        return view('auth.verify');
    }

    public function verifyOtp(VerifyOtpRequest $request, AuthService $authService): RedirectResponse
    {
        $phone = session('otp_phone');

        if (!$phone) {
            return redirect()
                ->route('auth.phone.form')
                ->with('error', 'Session expired. Please request OTP again.');
        }

        try {
            $user = $authService->verifyOtp($phone, $request->validated('otp'));

            if (!$user->is_active) {
                session()->forget('otp_phone');
                return redirect()->route('login')->with('error', 'Blocked by admin. Contact admin.');
            }

            Auth::login($user);

            session()->forget('otp_phone');
            session()->regenerate();

            return redirect()
                ->route('dashboard')
                ->with('success', 'Logged in successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function dashboard(): View
    {
        return view('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Logged out successfully.');
    }

    public function verifyEmail(Request $request, string $id, string $hash): RedirectResponse
    {
        $user = \App\Models\User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard')->with('success', 'Email already verified.');
        }

        if ($user->markEmailAsVerified()) {
            event(new \Illuminate\Auth\Events\Verified($user));
        }

        Auth::login($user);
        session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Email verified successfully! Welcome aboard.');
    }

    public function resendVerificationEmail(Request $request): RedirectResponse
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent!');
    }
}