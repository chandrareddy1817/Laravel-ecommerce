<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id(),
        ]);

        $cart->load('items.product');

        return view('frontend.cart', compact('cart'));
    }


    public function add(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if (!$product->status || !$product->category->status) {
            abort(404);
        }

        if ($product->stock < $validated['quantity']) {
            return back()->with('error', 'Not enough stock available.');
        }

        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id(),
        ]);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {

            $newQuantity = $cartItem->quantity + $validated['quantity'];

            if ($newQuantity > $product->stock) {
                return back()->with(
                    'error',
                    'You cannot add more than the available stock.'
                );
            }

            $cartItem->update([
                'quantity' => $newQuantity,
            ]);

        } else {

            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
            ]);
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Product added to cart.');
    }
    public function update(Request $request, CartItem $cartItem)
    {
        // Make sure this cart item belongs to the logged-in user
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = $cartItem->product;

        // Check current stock
        if ($validated['quantity'] > $product->stock) {
            return back()->with(
                'error',
                'Only ' . $product->stock . ' items are available.'
            );
        }

        $cartItem->update([
            'quantity' => $validated['quantity'],
        ]);

        return back()->with(
            'success',
            'Cart updated successfully.'
        );
    }


    public function remove(CartItem $cartItem)
    {
        // Make sure this cart item belongs to the logged-in user
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with(
            'success',
            'Product removed from cart.'
        );
    }
    public function count()
    {
        $cart = Cart::where('user_id', auth()->id())
            ->with('items')
            ->first();

        $count = $cart
            ? $cart->items->sum('quantity')
            : 0;

        return response()->json([
            'count' => $count,
        ]);
    }
}