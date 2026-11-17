<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Language;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
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
        Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            static $shared = null;

            if ($shared === null) {
                try {
                    if (Schema::hasTable('categories')) {
                        $shared = [
                            'globalMenuCategories' => Category::where('status', true)
                                ->where('show_on_menu', true)
                                ->orderBy('order')
                                ->take(7)
                                ->get(),
                            'globalLanguages' => Schema::hasTable('languages')
                                ? Language::where('status', true)->get()
                                : collect(),
                            'globalSettings' => Schema::hasTable('settings')
                                ? Setting::pluck('value', 'key')->all()
                                : [],
                        ];
                    } else {
                        $shared = [
                            'globalMenuCategories' => collect(),
                            'globalLanguages' => collect(),
                            'globalSettings' => [],
                        ];
                    }
                } catch (\Throwable) {
                    $shared = [
                        'globalMenuCategories' => collect(),
                        'globalLanguages' => collect(),
                        'globalSettings' => [],
                    ];
                }
            }

            $view->with($shared);
        });
    }
}
