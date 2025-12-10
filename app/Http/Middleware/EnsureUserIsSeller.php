<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSeller
{

    public function handle(Request $request, Closure $next): Response
    {

        if (!auth()->check()) {
            return redirect()->route('login');
        }


        if (!auth()->user()->store) {
            return redirect()->route('seller.stores.create')
                ->with('error', 'Anda harus mendaftarkan toko terlebih dahulu.');
        }


        if (!auth()->user()->store->is_verified) {
            return redirect()->route('dashboard')
                ->with('error', 'Toko Anda masih menunggu verifikasi dari admin.');
        }

        return $next($request);
    }
}
