<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function showForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendToken(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower($request->input('email'));
        $user = User::where('email', $email)->first();

        if ($user) {
            $this->createAndSendToken($user, $request);
        }

        return back()->with(
            'status',
            'Jika alamat email tersebut terdaftar, kami telah mengirimkan kode verifikasi 6 digit.'
        );
    }

    public function resendToken(Request $request): RedirectResponse
    {
        return $this->sendToken($request);
    }

    private function createAndSendToken(User $user, Request $request): void
    {
        $token = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::transaction(function () use ($user, $token) {
            PasswordResetToken::where('email', $user->email)->delete();

            PasswordResetToken::create([
                'email' => $user->email,
                'token' => $token,
                'created_at' => now(),
                'expires_at' => now()->addMinutes(10),
                'verified' => false,
                'attempts' => 0,
            ]);
        });

        Mail::to($user->email)->queue(new ResetPasswordMail($token));

        session(['password_reset_pending_email' => $user->email]);

        Log::info('Password reset requested', [
            'email' => $user->email,
            'ip' => $request->ip(),
        ]);
    }
}
