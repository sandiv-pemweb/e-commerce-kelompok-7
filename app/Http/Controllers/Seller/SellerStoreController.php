<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\SellerStoreRequest;

class SellerStoreController extends Controller
{

    public function create()
    {

        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Admin tidak dapat memiliki toko.');
        }


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


    public function store(SellerStoreRequest $request)
    {

        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Admin tidak dapat memiliki toko.');
        }


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


        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            try {

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


        if (empty($validated['address_id'])) {
            $validated['address_id'] = 'ADDR-' . strtoupper(uniqid());
        }


        $store = auth()->user()->store()->create([
            ...$validated,
            'is_verified' => false,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Toko berhasil didaftarkan. Menunggu verifikasi dari admin.');
    }


    public function edit()
    {

        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Admin tidak dapat memiliki toko.');
        }

        $store = auth()->user()->store;

        if (!$store) {

            $deletedStore = auth()->user()->store()->onlyTrashed()->first();
            if ($deletedStore) {
                return redirect()->route('seller.stores.create');
            }

            return redirect()->route('seller.stores.create')
                ->with('error', 'Anda belum memiliki toko.');
        }

        return view('seller.stores.edit', compact('store'));
    }


    public function update(SellerStoreRequest $request)
    {

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


        if ($request->hasFile('logo')) {

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


    public function destroy()
    {

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


    public function restore()
    {

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
