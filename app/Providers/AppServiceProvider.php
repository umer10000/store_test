<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Manufacturer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            if (Auth::check()) {
                $setting = \App\Models\Settings::find(1);
                $setting['categories'] = Category::where('status', 1)->where('parent_id', 0)->where('orderbymenu', '>', 0)->with('subCategories')->where('mark', 1)->orderBy('orderbymenu', 'ASC')->get();
                $setting['brands'] = Manufacturer::where('status', 1)->orderBy('sort_order', 'asc')->get();
                //...with this variable
                $view->with('setting', $setting);
            } else {
                $setting = \App\Models\Settings::find(1);
                $setting['categories'] = Category::where('status', 1)->where('parent_id', 0)->where('orderbymenu', '>', 0)->with('subCategories')->where('mark', 1)->orderBy('orderbymenu', 'ASC')->get();
                $setting['brands'] = Manufacturer::where('status', 1)->orderBy('sort_order', 'asc')->get();

                $view->with('setting', $setting);
            }
        });
        Schema::defaultStringLength(191);
    }
}
