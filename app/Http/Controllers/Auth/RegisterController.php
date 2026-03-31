<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;

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
                'email' => 'required|email|unique:users,email',
                'password' => ['required', 'confirmed', Password::min(8)],
                'phone' => 'nullable|string|max:20',
            ]);

            Log::info('Register attempt', ['email' => $request->email]);

            $user = User::create([
                'id' => Str::uuid()->toString(),
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'is_active' => true,
            ]);

            Log::info('User registered successfully', ['user_id' => $user->id]);

            return redirect('/login')->with('success', 'Registrasi berhasil. Silakan login.');
        } catch (\Exception $e) {
            Log::error('Register failed', ['error' => $e->getMessage()]);
            
            return back()
                ->withErrors(['email' => 'Registrasi gagal: ' . $e->getMessage()])
                ->withInput();
        }
    }
}