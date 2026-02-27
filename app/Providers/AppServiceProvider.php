<?php

namespace App\Providers;

use App\Models\Category;
use App\Services\CartService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $navCategories = Category::with('children')
                ->whereNull('parent_id')
                ->orderBy('order')
                ->get();

            $cart = new CartService();

            $view->with('navCategories', $navCategories);
            $view->with('cartCount', $cart->count());
        });
    }
}
