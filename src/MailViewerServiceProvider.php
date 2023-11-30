<?php

namespace Label84\MailViewer;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Label84\MailViewer\Providers\EventServiceProvider;

class MailViewerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/mailviewer.php' => config_path('mailviewer.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/mailviewer'),
            ], 'views');

            if (!class_exists('CreateMailViewerItems')) {
                $this->publishes([
                    __DIR__.'/../database/migrations/create_mail_viewer_items_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_mail_viewer_items_table.php'),
                ], 'migrations');
            }
        }

        $this->registerRoutes();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'mailviewer');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/mailviewer.php',
            'mailviewer'
        );

        $this->app->register(EventServiceProvider::class);
    }

    protected function registerRoutes(): void
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    protected function routeConfiguration(): array
    {
        return [
            'prefix' => config('mailviewer.route.prefix'),
            'middleware' => config('mailviewer.route.middleware'),
        ];
    }
}
