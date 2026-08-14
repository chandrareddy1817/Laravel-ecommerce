<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.frontend', function ($view) {

            $cartCount = 0;

            if (Auth::check()) {

                $cart = Cart::with('items')
                    ->where('user_id', Auth::id())
                    ->first();

                if ($cart) {
                    $cartCount = $cart->items->sum('quantity');
                }
            }

            $view->with('cartCount', $cartCount);
        });
    }
}