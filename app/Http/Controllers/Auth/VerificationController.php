<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailVerification;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    public function showForm()
    {
        $email = session('pending_verification_email');
        if (!$email) {
            return redirect('/register')->withErrors(['email' => 'Sesi verifikasi telah kedaluwarsa atau tidak valid. Silakan daftar kembali.']);
        }

        $verification = EmailVerification::where('email', $email)->first();
        $isLocked = false;
        $lockMessage = '';

        if ($verification) {
            if ($verification->is_locked) {
                if ($verification->locked_until && $verification->locked_until->isFuture()) {
                    $isLocked = true;
                    $lockMessage = 'Email Anda terkunci selama 24 jam. Silakan hubungi admin atau tunggu hingga ' . $verification->locked_until->format('d-m-Y H:i:s');
                } else {
                    // Unlock automatically if lock time has passed
                    $verification->update([
                        'is_locked' => false,
                        'locked_until' => null,
                        'attempts' => 0
                    ]);
                }
            }
        }

        return view('auth.verify-otp', compact('email', 'isLocked', 'lockMessage', 'verification'));
    }

    public function verify(Request $request)
    {
        $email = session('pending_verification_email');
        if (!$email) {
            return redirect('/register')->withErrors(['email' => 'Sesi verifikasi telah kedaluwarsa atau tidak valid. Silakan daftar kembali.']);
        }

        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $verification = EmailVerification::where('email', $email)->first();

        if (!$verification) {
            return redirect('/register')->withErrors(['email' => 'Data verifikasi tidak ditemukan.']);
        }

        // Check if locked
        if ($verification->is_locked) {
            if ($verification->locked_until && $verification->locked_until->isFuture()) {
                return back()->withErrors(['otp' => 'Terlalu banyak percobaan gagal. Email dikunci selama 24 jam.']);
            } else {
                // Auto unlock
                $verification->update([
                    'is_locked' => false,
                    'locked_until' => null,
                    'attempts' => 0
                ]);
            }
        }

        // Compare hash
        $inputHash = hash('sha256', $request->otp);
        $isCorrect = hash_equals($verification->otp_code, $inputHash);
        $isExpired = $verification->expires_at->isPast();

        if ($isExpired) {
            return back()->withErrors(['otp' => 'Kode OTP telah kedaluwarsa. Silakan minta kirim ulang OTP.']);
        }

        if (!$isCorrect) {
            $newAttempts = $verification->attempts + 1;
            
            if ($newAttempts >= 5) {
                $verification->update([
                    'attempts' => $newAttempts,
                    'is_locked' => true,
                    'locked_until' => now()->addHours(24),
                ]);
                return back()->withErrors(['otp' => 'Terlalu banyak percobaan gagal. Email Anda dikunci selama 24 jam.']);
            } else {
                $verification->update([
                    'attempts' => $newAttempts,
                ]);
                $remaining = 5 - $newAttempts;
                return back()->withErrors(['otp' => "Kode OTP salah. Sisa {$remaining} percobaan."]);
            }
        }

        // OTP is correct and valid
        DB::transaction(function () use ($email, $verification) {
            User::where('email', $email)->update([
                'email_verified_at' => now(),
            ]);
            $verification->delete();
        });

        // Store flag for success page
        session()->forget('pending_verification_email');
        session(['verification_completed' => true]);

        return redirect('/verification-success');
    }

    public function resend(Request $request)
    {
        $email = session('pending_verification_email');
        if (!$email) {
            return redirect('/register')->withErrors(['email' => 'Sesi verifikasi telah kedaluwarsa atau tidak valid. Silakan daftar kembali.']);
        }

        $verification = EmailVerification::where('email', $email)->first();

        if ($verification) {
            if ($verification->is_locked && $verification->locked_until && $verification->locked_until->isFuture()) {
                return back()->withErrors(['otp' => 'Email Anda sedang dikunci. Tidak bisa mengirim ulang OTP.']);
            }
        }

        $otpPlain = (string) random_int(100000, 999999);

        DB::transaction(function () use ($email, $otpPlain) {
            EmailVerification::updateOrCreate(
                ['email' => $email],
                [
                    'otp_code' => hash('sha256', $otpPlain),
                    'attempts' => 0,
                    'is_locked' => false,
                    'locked_until' => null,
                    'expires_at' => now()->addMinutes(10),
                ]
            );

            Mail::to($email)->send(new OtpMail($otpPlain));
        });

        return back()->with('success', 'Kode OTP baru telah berhasil dikirim ke email Anda.');
    }

    public function success()
    {
        if (!session('verification_completed')) {
            return redirect('/login');
        }

        session()->forget('verification_completed');

        return view('auth.verification-success');
    }
}
