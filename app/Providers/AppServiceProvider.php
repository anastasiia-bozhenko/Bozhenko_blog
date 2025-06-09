<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\BlogPost;       // ДОДАЄМО: Імпорт моделі BlogPost
use App\Models\BlogCategory;  // ДОДАЄМО: Імпорт моделі BlogCategory
use App\Observers\BlogPostObserver;    // ДОДАЄМО: Імпорт Observer для BlogPost
use App\Observers\BlogCategoryObserver; // ДОДАЄМО: Імпорт Observer для BlogCategory

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
        // Реєстрація Observer'ів для відповідних моделей
        BlogPost::observe(BlogPostObserver::class);      // ДОДАЄМО
        BlogCategory::observe(BlogCategoryObserver::class); // ДОДАЄМО
    }
}
