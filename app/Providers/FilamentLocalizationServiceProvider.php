<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Lang;

class FilamentLocalizationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Tambahkan path JSON translation untuk Filament
        Lang::addJsonPath(resource_path('lang/vendor/filament'));
        // Paksa gunakan locale Indonesia di seluruh halaman Filament
        app()->setLocale('id');
    }
}
