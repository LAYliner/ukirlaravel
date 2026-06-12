<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    public function showVerifyForm(Request $request): View
    {
        $email = $request->query('email', session('password_reset_pending_email', old('email')));

        return view('auth.verify-token', compact('email'));
    }

    public function verify(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required', 'digits:6'],
        ]);

        $email = strtolower($validated['email']);
        $record = PasswordResetToken::where('email', $email)->first();

        if (!$record) {
            return back()->withInput(['email' => $email])->withErrors([
                'token' => 'Kode verifikasi tidak valid.',
            ]);
        }

        if ($record->attempts >= 5) {
            $record->delete();

            Log::warning('Token brute force detected', [
                'email' => $email,
                'ip' => $request->ip(),
            ]);

            return back()->withInput(['email' => $email])->withErrors([
                'token' => 'Terlalu banyak percobaan. Silakan minta kode baru.',
            ]);
        }

        if (!$record->expires_at || $record->expires_at->isPast()) {
            $record->delete();

            return back()->withInput(['email' => $email])->withErrors([
                'token' => 'Kode verifikasi telah kedaluwarsa. Silakan minta ulang.',
            ]);
        }

        if (!hash_equals($record->token, $validated['token'])) {
            $record->increment('attempts');
            $remaining = max(0, 5 - $record->fresh()->attempts);

            Log::info('Token verification failed', [
                'email' => $email,
                'ip' => $request->ip(),
            ]);

            return back()->withInput(['email' => $email])->withErrors([
                'token' => "Kode verifikasi tidak valid. Sisa percobaan: {$remaining}.",
            ]);
        }

        $record->update(['verified' => true]);

        session([
            'password_reset_email' => $email,
            'password_reset_verified_at' => now()->timestamp,
        ]);

        Log::info('Token verification successful', ['email' => $email]);

        return redirect()->route('password.reset');
    }

    public function showResetForm(): RedirectResponse|View
    {
        if (!$this->hasValidVerifiedSession()) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi verifikasi tidak valid atau sudah kedaluwarsa. Silakan ulangi.']);
        }

        return view('auth.reset-password');
    }

    public function reset(Request $request): RedirectResponse
    {
        if (!$this->hasValidVerifiedSession()) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi verifikasi tidak valid atau sudah kedaluwarsa. Silakan ulangi.']);
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = session('password_reset_email');
        $record = PasswordResetToken::where('email', $email)
            ->where('verified', true)
            ->first();

        if (!$record) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Verifikasi tidak ditemukan. Silakan ulangi proses.']);
        }

        $user = User::where('email', $email)->firstOrFail();
        $user->forceFill([
            'password' => Hash::make($request->input('password')),
        ])->save();

        event(new PasswordReset($user));

        $record->delete();
        Session::forget([
            'password_reset_pending_email',
            'password_reset_email',
            'password_reset_verified_at',
        ]);

        Log::info('Password reset successful', ['email' => $email]);

        return redirect()->route('login.show')
            ->with('success', 'Password berhasil diubah. Silakan login.');
    }

    private function hasValidVerifiedSession(): bool
    {
        $email = session('password_reset_email');
        $verifiedAt = session('password_reset_verified_at');

        if (!$email || !$verifiedAt || now()->timestamp - (int) $verifiedAt > 900) {
            return false;
        }

        return PasswordResetToken::where('email', $email)
            ->where('verified', true)
            ->exists();
    }
}
