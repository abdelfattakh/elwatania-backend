<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Page;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            $data = array(
                'parentcategories' => Category::query()->active()->whereNull('parent_id')->with(['children' => fn($q) => $q->active(),])->take(6)->get(),
                'customer_pages' => Page::active()->where('show->en', 'customer_service')->get(),
                'about_pages' => Page::active()->where('show->en', 'about_watania')->get(),
            );
            View::share('data', $data);
        } catch (\Exception) {
            View::share('data', []);
        }

    }
}
