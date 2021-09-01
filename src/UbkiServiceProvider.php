<?php

namespace Arttiger\Ubki;

    use Illuminate\Support\ServiceProvider;

    class UbkiServiceProvider extends ServiceProvider
    {
        /**
         * Perform post-registration booting of services.
         *
         * @return void
         */
        public function boot()
        {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

            $this->publishes([
                __DIR__.'/../config/ubki.php' => config_path('ubki.php'),
            ], 'ubki.config');

            // Publishing is only necessary when using the CLI.
            if ($this->app->runningInConsole()) {
                $this->bootForConsole();
            }
        }

        /**
         * Register any package services.
         *
         * @return void
         */
        public function register()
        {
            $this->mergeConfigFrom(__DIR__.'/../config/ubki.php', 'ubki');

            // Register the service the package provides.
            $this->app->singleton('ubki', function ($app) {
                return new Ubki;
            });
        }

        /**
         * Get the services provided by the provider.
         *
         * @return array
         */
        public function provides()
        {
            return ['ubki'];
        }

        /**
         * Console-specific booting.
         *
         * @return void
         */
        protected function bootForConsole()
        {
            // Publishing the configuration file.
            $this->publishes([
                __DIR__.'/../config/ubki.php' => config_path('ubki.php'),
            ], 'ubki.config');

            // Publish migrations
            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('/migrations'),
            ], 'ubki.migrations');
        }
    }
