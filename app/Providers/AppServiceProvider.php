<?php

namespace App\Providers;

use App\Interfaces\IGroupManagement;
use App\Services\GroupManagementServices;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this -> app -> bind(IGroupManagement::class, GroupManagementServices::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
