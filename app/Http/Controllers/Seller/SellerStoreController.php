<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\SellerStoreRequest;

class SellerStoreController extends Controller
{
    /**
     * Show the form for creating a new store.
     */
    public function create()
    {
        // Prevent admin from creating stores
        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                           ->with('error', 'Admin tidak dapat memiliki toko.');
        }

        // Check if user already has a store (including soft deleted ones)
        $store = auth()->user()->store()->withTrashed()->first();

        if ($store) {
            if ($store->trashed()) {
                return view('seller.stores.restore', compact('store'));
            }

            return redirect()->route('seller.stores.edit')
                           ->with('info', 'Anda sudah memiliki toko. Silakan edit profil toko Anda.');
        }

        return view('seller.stores.create');
    }

    /**
     * Store a newly created store in storage.
     */
    public function store(SellerStoreRequest $request)
    {
        // Prevent admin from creating stores
        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                           ->with('error', 'Admin tidak dapat memiliki toko.');
        }

        // Check if user already has a store (including soft deleted ones)
        $existingStore = auth()->user()->store()->withTrashed()->first();

        if ($existingStore) {
            if ($existingStore->trashed()) {
                return redirect()->route('seller.stores.create')
                               ->with('info', 'Anda memiliki toko yang dihapus. Silakan pulihkan toko Anda.');
            }
            
            return redirect()->route('seller.stores.edit')
                           ->with('error', 'Anda sudah memiliki toko.');
        }

        $validated = $request->validated();

        // Handle logo upload
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            try {
                // Laravel's recommended way - simple and clean
                $logoPath = $request->file('logo')->store('store-logos', 'public');
                
                if (!$logoPath) {
                    throw new \Exception('Failed to store file');
                }
                
                $validated['logo'] = $logoPath;
                
            } catch (\Exception $e) {
                \Log::error('File upload error', [
                    'message' => $e->getMessage(),
                    'type' => get_class($e)
                ]);
                
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Gagal mengunggah logo. Error: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Logo toko harus diunggah dan harus berupa file gambar yang valid.');
        }

        // Auto-generate address_id if not provided
        if (empty($validated['address_id'])) {
            $validated['address_id'] = 'ADDR-' . strtoupper(uniqid());
        }

        // Create store
        $store = auth()->user()->store()->create([
            ...$validated,
            'is_verified' => false,
        ]);

        return redirect()->route('dashboard')
                       ->with('success', 'Toko berhasil didaftarkan. Menunggu verifikasi dari admin.');
    }

    /**
     * Show the form for editing the store.
     */
    public function edit()
    {
        // Prevent admin from editing stores
        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                           ->with('error', 'Admin tidak dapat memiliki toko.');
        }

        $store = auth()->user()->store;

        if (!$store) {
            // Check if there is a deleted store
            $deletedStore = auth()->user()->store()->onlyTrashed()->first();
            if ($deletedStore) {
                return redirect()->route('seller.stores.create');
            }

            return redirect()->route('seller.stores.create')
                           ->with('error', 'Anda belum memiliki toko.');
        }

        return view('seller.stores.edit', compact('store'));
    }

    /**
     * Update the store in storage.
     */
    public function update(SellerStoreRequest $request)
    {
        // Prevent admin from updating stores
        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                           ->with('error', 'Admin tidak dapat memiliki toko.');
        }

        $store = auth()->user()->store;

        if (!$store) {
            return redirect()->route('seller.stores.create')
                           ->with('error', 'Anda belum memiliki toko.');
        }

        $validated = $request->validated();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($store->logo && Storage::disk('public')->exists($store->logo)) {
                Storage::disk('public')->delete($store->logo);
            }

            $logoPath = $request->file('logo')->store('store-logos', 'public');
            $validated['logo'] = $logoPath;
        }

        $store->update($validated);

        return redirect()->route('seller.stores.edit')
                       ->with('success', 'Profil toko berhasil diperbarui.');
    }

    /**
     * Remove the store from storage (soft delete).
     */
    public function destroy()
    {
        // Prevent admin from deleting stores
        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                           ->with('error', 'Admin tidak dapat memiliki toko.');
        }

        $store = auth()->user()->store;

        if (!$store) {
            return redirect()->route('dashboard')
                           ->with('error', 'Anda tidak memiliki toko.');
        }

        $store->delete();

        return redirect()->route('dashboard')
                       ->with('success', 'Toko berhasil dihapus. Anda dapat memulihkannya kapan saja.');
    }

    /**
     * Restore the soft deleted store.
     */
    public function restore()
    {
        // Prevent admin from restoring stores
        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                           ->with('error', 'Admin tidak dapat memiliki toko.');
        }

        $store = auth()->user()->store()->onlyTrashed()->first();

        if (!$store) {
            return redirect()->route('dashboard')
                           ->with('error', 'Tidak ada toko yang dapat dipulihkan.');
        }

        $store->restore();

        return redirect()->route('seller.stores.edit')
                       ->with('success', 'Toko berhasil dipulihkan.');
    }
}
