<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSeller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if user has a store
        if (!auth()->user()->store) {
            return redirect()->route('seller.stores.create')
                           ->with('error', 'Anda harus mendaftarkan toko terlebih dahulu.');
        }

        // Check if store is verified
        if (!auth()->user()->store->is_verified) {
            return redirect()->route('dashboard')
                           ->with('error', 'Toko Anda masih menunggu verifikasi dari admin.');
        }

        return $next($request);
    }
}
