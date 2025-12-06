<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * Display a listing of all users with their stores.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::with('store')->latest();

        // Filter by search query
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
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

    /**
     * Display the specified user with store details.
     * 
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        // Load store relationship
        $user->load('store');
        
        // Only load store details if user has a store
        if ($user->store) {
            $user->store->loadCount('products')
                        ->load('storeBalance');
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,member',
        ]);

        // Prevent store owners from becoming admins
        if ($validated['role'] === 'admin' && $user->store()->exists()) {
            return back()->with('error', 'Pengguna yang memiliki toko tidak dapat dijadikan Administrator.');
        }

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
                       ->with('success', 'Role pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                       ->with('success', 'Pengguna berhasil dihapus.');
    }
}
