<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    public function index()
    {
        $cart    = $this->cartService->getCart();
        $cart->load(['items.product.images', 'items.variant.size', 'items.variant.color', 'coupon']);
        $summary = $this->cartService->getSummary($cart);

        return view('shop.cart', compact('cart', 'summary'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'nullable|integer|min:1|max:50',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
        ]);

        $result = $this->cartService->add(
            $request->product_id,
            $request->input('quantity', 1),
            $request->variant_id
        );

        if ($request->ajax()) {
            return response()->json($result, $result['success'] ? 200 : 422);
        }

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->withErrors(['cart' => $result['message']]);
    }

    public function update(Request $request, int $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0|max:50',
        ]);

        $result = $this->cartService->update($itemId, $request->quantity);

        if ($request->ajax()) {
            $cart    = $this->cartService->getCart();
            $cart->load(['items.product', 'items.variant', 'coupon']);
            $summary = $this->cartService->getSummary($cart);

            return response()->json(array_merge($result, [
                'summary' => $summary,
                'count'   => $this->cartService->getItemCount(),
            ]), $result['success'] ? 200 : 422);
        }

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function remove(Request $request, int $itemId)
    {
        $result = $this->cartService->remove($itemId);

        if ($request->ajax()) {
            $cart    = $this->cartService->getCart();
            $cart->load(['items.product', 'items.variant', 'coupon']);
            $summary = $this->cartService->getSummary($cart);

            return response()->json(array_merge($result, [
                'summary' => $summary,
                'count'   => $result['count'] ?? 0,
            ]), $result['success'] ? 200 : 422);
        }

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string|max:50']);

        $result = $this->cartService->applyCoupon($request->code);

        if ($request->ajax()) {
            return response()->json($result, $result['success'] ? 200 : 422);
        }

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function removeCoupon(Request $request)
    {
        $this->cartService->removeCoupon();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Cupón eliminado.']);
        }

        return back()->with('success', 'Cupón eliminado.');
    }
}
