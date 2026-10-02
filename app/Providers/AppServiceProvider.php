<?php

namespace App\Providers;

use App\Models\Jurusan;
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
        View::composer('Public.layout.header', function ($view): void {
            $view->with('navJurusan', Jurusan::query()->orderBy('jurusanID')->get(['jurusanID', 'nama']));
        });
    }
}
