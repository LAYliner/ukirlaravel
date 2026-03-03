<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'nomor' => 'nullable|string|max:15',
        ]);

        User::create([
            'id' => Str::uuid()->toString(),
            'nama' => $request->nama,
            'email' => $request->email,
            'nomor' => $request->nomor,
            'password' => Hash::make($request->password),
            'role' => 'user', // Force role user
            'aktif' => true,
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil. Silakan login.');
    }
}
