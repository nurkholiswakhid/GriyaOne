<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index()
    {
        $search = request('search');
        $roleFilter = request('role');

        $query = User::query();

        // Search by name or email
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($roleFilter) {
            $query->where('role', $roleFilter);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->appends(request()->query());

        $stats = [
            'total' => User::count(),
            'users' => User::where('role', 'user')->count(),
            'marketing' => User::where('role', 'marketing')->count(),
            'admins' => User::where('role', 'admin')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats', 'search', 'roleFilter'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:user,marketing,admin',
        ]);

        // Auto-generate password if not provided
        if (empty($validated['password'])) {
            $validated['password'] = strtolower(str_replace(' ', '', $validated['name'])) . '123456';
        }

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return redirect()->route('users.show', $user)
            ->with('success', 'Profil user berhasil diperbarui');
    }

    /**
     * Reset user password to default format.
     */
    public function resetPassword(User $user)
    {
        $defaultPassword = strtolower(str_replace(' ', '', $user->name)) . '123456';
        $user->update(['password' => bcrypt($defaultPassword)]);

        return redirect()->route('users.show', $user)
            ->with('success', "Password user '{$user->name}' berhasil direset ke: <strong>{$defaultPassword}</strong>");
    }

    /**
     * Update user role.
     */
    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:user,marketing,admin',
        ]);

        $user->update($validated);

        return redirect()->back()
            ->with('success', "Role user '{$user->name}' berhasil diubah menjadi {$validated['role']}");
    }

    /**
     * Delete the specified user.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat menghapus akun sendiri');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "User '{$name}' berhasil dihapus");
    }
}
