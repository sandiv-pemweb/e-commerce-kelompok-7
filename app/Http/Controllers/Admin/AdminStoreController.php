<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreBalance;
use Illuminate\Http\Request;

class AdminStoreController extends Controller
{
    /**
     * Display a listing of all stores with user and product count.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Store::with('user')
                     ->withCount('products')
                     ->latest();

        // Filter by verification status
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('is_verified', false);
            } elseif ($request->status === 'verified') {
                $query->where('is_verified', true);
            } elseif ($request->status === 'deleted') {
                $query->onlyTrashed();
            }
        } else {
            // When no filter is selected, show all stores including trashed ones
            $query->withTrashed();
        }

        $stores = $query->paginate(20);

        return view('admin.stores.index', compact('stores'));
    }

    /**
     * Display the specified store with all relationships.
     * 
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $store = Store::withTrashed()->findOrFail($id);
        
        $store->load([
            'user',
            'products' => fn($query) => $query->latest()->take(10),
            'storeBalance'
        ])
        ->loadCount(['products', 'transactions']);

        return view('admin.stores.show', compact('store'));
    }

    /**
     * Verify (approve) a store registration.
     */
    public function verify(Store $store)
    {
        if ($store->is_verified) {
            return back()->with('info', 'Toko sudah terverifikasi.');
        }

        $store->update(['is_verified' => true]);

        // Create store balance if it doesn't exist
        if (!$store->storeBalance) {
            $store->storeBalance()->create([
                'balance' => 0,
            ]);
        }

        return redirect()->route('admin.stores.show', $store)
                       ->with('success', 'Toko berhasil diverifikasi.');
    }

    /**
     * Reject a store registration.
     */
    public function reject(Store $store)
    {
        if ($store->is_verified) {
            return back()->with('error', 'Toko yang sudah terverifikasi tidak dapat ditolak.');
        }

        $store->delete();

        return redirect()->route('admin.stores.index')
                       ->with('success', 'Pengajuan toko ditolak dan dihapus.');
    }

    /**
     * Remove the specified store from storage.
     */
    public function destroy(Store $store)
    {
        $store->delete();

        return redirect()->route('admin.stores.index')
                       ->with('success', 'Toko berhasil dihapus.');
    }

    /**
     * Restore the specified deleted store.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $store = Store::onlyTrashed()->findOrFail($id);
        $store->restore();

        return redirect()->route('admin.stores.index')
                       ->with('success', 'Toko berhasil dipulihkan.');
    }
}
