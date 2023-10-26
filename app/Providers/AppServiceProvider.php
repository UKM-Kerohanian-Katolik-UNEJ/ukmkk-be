<?php

namespace App\Providers;

use App\Models\Member;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer("layouts.top-menu", function($view)
        {
            $view->with([
                "notification" => Member::whereIsVerified("Pending")->get()
            ]);
        });
    }
}
