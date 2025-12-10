<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Services\StoreService;
use Exception;

class AdminStoreController extends Controller
{
    public function __construct(protected StoreService $storeService)
    {
    }

    public function index(Request $request)
    {
        $query = Store::with('user')->withCount('products')->latest();

        if ($request->filled('q')) {
            $query->where('name', 'like', "%{$request->q}%");
        }

        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('is_verified', false);
            } elseif ($request->status === 'verified') {
                $query->where('is_verified', true);
            } elseif ($request->status === 'deleted') {
                $query->onlyTrashed();
            }
        } else {
            $query->withTrashed();
        }

        $stores = $query->paginate(20);

        return view('admin.stores.index', compact('stores'));
    }

    public function show(Store $store)
    {
        $store->load(['user', 'products' => fn($query) => $query->latest()->take(10), 'storeBalance'])
              ->loadCount(['products', 'transactions']);

        return view('admin.stores.show', compact('store'));
    }

    public function verify(Store $store)
    {
        try {
            $this->storeService->verifyStore($store);
            return redirect()->route('admin.stores.show', $store)->with('success', 'Toko berhasil diverifikasi.');
        } catch (Exception $e) {
            return back()->with('info', $e->getMessage());
        }
    }

    public function reject(Store $store)
    {
        if ($store->is_verified) {
             return back()->with('error', 'Toko yang sudah terverifikasi tidak dapat ditolak.');
        }
        $this->storeService->deleteStore($store);
        return redirect()->route('admin.stores.index')->with('success', 'Pengajuan toko ditolak dan dihapus.');
    }

    public function destroy(Store $store)
    {
        $this->storeService->deleteStore($store);
        return redirect()->route('admin.stores.index')->with('success', 'Toko berhasil dihapus.');
    }

    public function restore(Store $store)
    {
        $store->restore(); // Directly reusing model method or can add to service if needed for generic restore by ID
        return redirect()->route('admin.stores.index')->with('success', 'Toko berhasil dipulihkan.');
    }
}
