<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use App\Services\WishlistService;
use Exception;

class WishlistController extends Controller
{
    public function __construct(protected WishlistService $wishlistService)
    {
    }

    public function index()
    {
        $wishlists = $this->wishlistService->getUserWishlist(Auth::id());
        return view('wishlist.index', compact('wishlists'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $result = $this->wishlistService->toggleWishlist(Auth::id(), $request->product_id);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                ...$result
            ]);
        }

        return redirect()->back()->with('success', $result['message']);
    }

    public function destroy(Request $request, Wishlist $wishlist)
    {
        try {
            $result = $this->wishlistService->deleteWishlist($wishlist, Auth::id());

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    ...$result
                ]);
            }

            return redirect()->back()->with('success', $result['message']);
        } catch (Exception $e) {
             if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], $e->getCode() ?: 500);
            }
            abort($e->getCode() ?: 500);
        }
    }
}
