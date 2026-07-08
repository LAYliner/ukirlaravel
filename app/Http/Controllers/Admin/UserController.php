<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users with pagination, sorting, search, and filters.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        // Search by name or email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($role = $request->input('role')) {
            if (in_array($role, ['admin', 'author', 'user'])) {
                $query->where('role', $role);
            }
        }

        // Filter by status
        if ($status = $request->input('status')) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($status === 'deleted') {
                $query->onlyTrashed();
            } elseif ($status === 'all') {
                $query->withTrashed();
            }
        }

        // Sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $allowedSortFields = ['id', 'name', 'email', 'role', 'is_active', 'created_at', 'updated_at'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        if (!in_array(strtoupper($sortDirection), ['ASC', 'DESC'])) {
            $sortDirection = 'desc';
        }

        $query->orderBy($sortField, $sortDirection);

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Toggle user status (active/inactive).
     */
    public function toggleStatus(string $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        // Prevent toggling own status
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat mengubah status akun sendiri.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $statusText = $user->is_active ? 'aktif' : 'non-aktif';
        return redirect()->route('admin.users.index')
            ->with('success', "Status user berhasil diubah menjadi {$statusText}.");
    }

    /**
     * Update user role.
     */
    public function updateRole(Request $request, string $id): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'author', 'user'])],
        ]);

        $user = User::findOrFail($id);

        // Prevent changing own role
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat mengubah role akun sendiri.');
        }

        $user->role = $validated['role'];
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', "Role user berhasil diubah menjadi {$validated['role']}.");
    }

    /**
     * Soft delete a user.
     */
    public function destroy(string $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete(); // Soft delete

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Restore a soft-deleted user.
     */
    public function restore(string $id): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dipulihkan.');
    }

    /**
     * Permanently delete a user.
     */
    public function forceDelete(string $id): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($id);

        // Prevent force deleting own account
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri secara permanen.');
        }

        // Delete profile photo if exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->forceDelete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus permanen.');
    }
}