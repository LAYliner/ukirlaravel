<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailVerification;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'password' => ['required', 'confirmed', Password::min(8)],
                'phone' => 'nullable|string|max:20',
                'role' => 'required|string|in:user,author',
            ]);

            Log::info('Register attempt', ['email' => $request->email]);

            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser) {
                if ($existingUser->email_verified_at !== null) {
                    Log::info('User already exists and is verified', ['email' => $request->email]);
                    return redirect('/login')->with('success', 'Registrasi berhasil. Silakan cek email Anda untuk instruksi lebih lanjut.');
                } else {
                    Log::info('Unverified user registering again, updating info and sending new OTP', ['email' => $request->email]);
                    DB::transaction(function() use ($existingUser, $request) {
                        $existingUser->update([
                            'name' => $request->name,
                            'phone' => $request->phone,
                            'password' => Hash::make($request->password),
                            'role' => $request->role,
                        ]);

                        // Generate OTP
                        $otpPlain = (string) random_int(100000, 999999);
                        EmailVerification::updateOrCreate(
                            ['email' => $request->email],
                            [
                                'otp_code' => hash('sha256', $otpPlain),
                                'attempts' => 0,
                                'is_locked' => false,
                                'locked_until' => null,
                                'expires_at' => now()->addMinutes(10),
                            ]
                        );

                        Mail::to($request->email)->send(new OtpMail($otpPlain));
                    });

                    session(['pending_verification_email' => $request->email]);
                    return redirect('/verify-otp')->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
                }
            }

            DB::transaction(function () use ($request) {
                $user = User::create([
                    'id' => Str::uuid()->toString(),
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'role' => $request->role,
                    'is_active' => true,
                    'email_verified_at' => null,
                ]);

                Log::info('User registered successfully', ['user_id' => $user->id]);

                // Generate OTP
                $otpPlain = (string) random_int(100000, 999999);
                EmailVerification::create([
                    'email' => $request->email,
                    'otp_code' => hash('sha256', $otpPlain),
                    'attempts' => 0,
                    'is_locked' => false,
                    'locked_until' => null,
                    'expires_at' => now()->addMinutes(10),
                ]);

                Mail::to($request->email)->send(new OtpMail($otpPlain));
            });

            session(['pending_verification_email' => $request->email]);

            return redirect('/verify-otp')->with('success', 'Registrasi berhasil. Silakan masukkan kode OTP yang dikirim ke email Anda.');
        } catch (\Exception $e) {
            Log::error('Register failed', ['error' => $e->getMessage()]);
            
            return back()
                ->withErrors(['email' => 'Registrasi gagal: ' . $e->getMessage()])
                ->withInput();
        }
    }
}