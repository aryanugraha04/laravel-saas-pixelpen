<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OpenAI;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\OpenAI\Client::class, function ($app) {
            return \OpenAI::factory()
                ->withApiKey(config('services.groq.api_key')) // Mengambil API Key dari config/services.php
                ->withBaseUri('https://api.groq.com/openai/v1') // INI BAGIAN PALING PENTING: Mengarahkan ke Groq
                ->make();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
