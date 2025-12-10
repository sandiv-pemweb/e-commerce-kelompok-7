<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Exception;

class AdminUserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
    }

    public function index(Request $request)
    {
        $users = $this->userService->getUsers($request->all());
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('store');
        if ($user->store) {
            $user->store->loadCount('products')->load('storeBalance');
        }
        return view('admin.users.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,member',
        ]);

        try {
            $this->userService->changeRole($user, $validated['role']);
            return redirect()->route('admin.users.show', $user)
                ->with('success', 'Role pengguna berhasil diperbarui.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $this->userService->deleteUser($user);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
