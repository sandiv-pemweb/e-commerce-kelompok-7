<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{

    public function index(Request $request)
    {
        $query = User::with('store')->latest();


        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }


        if ($request->filled('role')) {
            if ($request->role === 'admin') {
                $query->where('role', 'admin');
            } elseif ($request->role === 'seller') {
                $query->whereHas('store');
            } elseif ($request->role === 'buyer') {
                $query->where('role', 'member')->whereDoesntHave('store');
            }
        }

        $users = $query->paginate(20);

        return view('admin.users.index', compact('users'));
    }


    public function show(User $user)
    {

        $user->load('store');


        if ($user->store) {
            $user->store->loadCount('products')
                ->load('storeBalance');
        }

        return view('admin.users.show', compact('user'));
    }


    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,member',
        ]);


        if ($validated['role'] === 'admin' && $user->store()->exists()) {
            return back()->with('error', 'Pengguna yang memiliki toko tidak dapat dijadikan Administrator.');
        }

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Role pengguna berhasil diperbarui.');
    }


    public function destroy(User $user)
    {

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
