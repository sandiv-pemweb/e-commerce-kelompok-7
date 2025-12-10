<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

use App\Http\Requests\SellerStoreRequest;
use App\Services\StoreService;
use Illuminate\Support\Facades\Auth;
use Exception;

class SellerStoreController extends Controller
{
    public function __construct(protected StoreService $storeService)
    {
    }

    public function create()
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Admin tidak dapat memiliki toko.');
        }

        $store = $this->storeService->getUserStore(Auth::id(), true);

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
            return redirect()->route('dashboard')->with('error', 'Admin tidak dapat memiliki toko.');
        }

        $existingStore = $this->storeService->getUserStore(Auth::id(), true);

        if ($existingStore) {
            if ($existingStore->trashed()) {
                return redirect()->route('seller.stores.create')
                    ->with('info', 'Anda memiliki toko yang dihapus. Silakan pulihkan toko Anda.');
            }
            return redirect()->route('seller.stores.edit')->with('error', 'Anda sudah memiliki toko.');
        }

        try {
            $this->storeService->createStore(Auth::id(), $request->validated(), $request->file('logo'));
            return redirect()->route('dashboard')
                ->with('success', 'Toko berhasil didaftarkan. Menunggu verifikasi dari admin.');
        } catch (Exception $e) {
             return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat toko: ' . $e->getMessage());
        }
    }


    public function edit()
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Admin tidak dapat memiliki toko.');
        }

        $store = $this->storeService->getUserStore(Auth::id());

        if (!$store) {
            $deletedStore = $this->storeService->getUserStore(Auth::id(), true);
            if ($deletedStore && $deletedStore->trashed()) {
                return redirect()->route('seller.stores.create');
            }
            return redirect()->route('seller.stores.create')->with('error', 'Anda belum memiliki toko.');
        }

        return view('seller.stores.edit', compact('store'));
    }


    public function update(SellerStoreRequest $request)
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Admin tidak dapat memiliki toko.');
        }

        $store = $this->storeService->getUserStore(Auth::id());

        if (!$store) {
            return redirect()->route('seller.stores.create')->with('error', 'Anda belum memiliki toko.');
        }

        try {
             $this->storeService->updateStore($store, $request->validated(), $request->file('logo'));
             return redirect()->route('seller.stores.edit')
                ->with('success', 'Profil toko berhasil diperbarui.');
        } catch (Exception $e) {
             return redirect()->back()->with('error', 'Gagal memperbarui toko: ' . $e->getMessage());
        }
    }


    public function destroy()
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Admin tidak dapat memiliki toko.');
        }

        $store = $this->storeService->getUserStore(Auth::id());

        if (!$store) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki toko.');
        }

        $this->storeService->deleteStore($store);

        return redirect()->route('dashboard')
            ->with('success', 'Toko berhasil dihapus. Anda dapat memulihkannya kapan saja.');
    }


    public function restore()
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Admin tidak dapat memiliki toko.');
        }

        try {
            $this->storeService->restoreStore(Auth::id());
            return redirect()->route('seller.stores.edit')->with('success', 'Toko berhasil dipulihkan.');
        } catch (Exception $e) {
            return redirect()->route('dashboard')->with('error', $e->getMessage());
        }
    }
}
