<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Language;

class LanguageProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // $locale = session('app_locale');
        // $language = Language::where('canonical', $locale)->first();
        // dd($language);
    }
}
