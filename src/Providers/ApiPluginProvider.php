<?php

namespace blizko\LibrenmsAPIPlugin\Providers;

use App\Plugins\Hooks\MenuEntryHook;
use App\Plugins\PluginManager;
use Illuminate\Support\ServiceProvider;
use blizko\LibrenmsAPIPlugin\Hooks\MenuHook;

class ApiPluginProvider extends ServiceProvider
{
    /**
     * Register routes during the register phase so they land in the router
     * BEFORE LibreNMS's own route service provider runs in boot() and adds its
     * catch-all web route (Route::any('/{path?}', ...)->middleware('auth')).
     * Laravel resolves routes first-match; if the catch-all wins, every plugin
     * request gets a 302 redirect to /login regardless of middleware.
     */
    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
    }

    public function boot(PluginManager $manager): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'api-plugin');

        $name = 'api-plugin';
        $manager->publishHook($name, MenuEntryHook::class, MenuHook::class);
    }
}
