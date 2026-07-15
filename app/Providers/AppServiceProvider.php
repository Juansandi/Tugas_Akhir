<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Models\UserNotification;
use App\Models\Cart;    

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
        URL::forceScheme('https');
        }
        Paginator::useBootstrapFive();

        // ===============================
        // NAVBAR CUSTOMER DATA
        // ===============================
        View::composer('layouts.app', function ($view) {

            if (Auth::check()) {
                $unreadNotifications = UserNotification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->latest()
                    ->take(5)
                    ->get();

                $unreadNotifCount = UserNotification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count();

                $cartCount = Cart::where('user_id', Auth::id())->count();
            } else {
                $unreadNotifications = collect();
                $unreadNotifCount = 0;
                $cartCount = 0;
            }

            $view->with([
                'navbarNotifs' => $unreadNotifications,
                'navbarNotifCount' => $unreadNotifCount,
                'cartCount' => $cartCount,
            ]);
        });
    }
}
