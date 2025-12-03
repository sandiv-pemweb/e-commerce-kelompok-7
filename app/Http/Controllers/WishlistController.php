<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with('product.store')
            ->latest()
            ->paginate(10);
            
        return view('wishlist.index', compact('wishlists'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $added = false;
            $message = 'Produk dihapus dari wishlist';
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
            ]);
            $added = true;
            $message = 'Produk berhasil ditambahkan ke wishlist';
        }

        $count = Wishlist::where('user_id', Auth::id())->count();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'added' => $added,
                'message' => $message,
                'count' => $count
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function destroy(Request $request, Wishlist $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403);
        }

        $wishlist->delete();

        if ($request->wantsJson()) {
            $count = Wishlist::where('user_id', Auth::id())->count();
            return response()->json([
                'success' => true,
                'message' => 'Produk dihapus dari wishlist',
                'count' => $count
            ]);
        }

        return redirect()->back()->with('success', 'Produk dihapus dari wishlist');
    }
}
