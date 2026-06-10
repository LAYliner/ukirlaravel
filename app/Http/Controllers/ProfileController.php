<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        // Handle file upload
        if ($request->hasFile('profile_picture')) {
            // Delete old file
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Store new file
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $user->profile_picture = $path;
        }

        // Exclude profile_picture dari validated() agar tidak di-overwrite oleh mass assignment
        $user->update($request->safe()->except(['profile_picture']));

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Password saat ini tidak sesuai.'],
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Password berhasil diubah.');
    }

    public function comments(Request $request)
    {
        $user = Auth::user();

        $comments = $user->comments()
            ->with('commentable') // Eager load Blog/Project
            ->latest()
            ->paginate(10);

        return view('profile.comments', compact('comments'));
    }
}
