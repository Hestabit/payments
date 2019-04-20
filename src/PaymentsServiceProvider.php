<?php

namespace Hetsabit\Payments;

use Illuminate\Support\ServiceProvider;
use Hetsabit\Payments\Repositories\PayPal;
use Hetsabit\Payments\Repositories\StripePay;

/*
|--------------------------------------------------
| Service provider for handling the control of Payment gateways
|--------------------------------------------------
| Written By- Hetsabit
*/
class PaymentsServiceProvider extends ServiceProvider{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(){
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'Hetsabit');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'Hetsabit');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

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
    public function register(){
        $this->mergeConfigFrom(__DIR__.'/../config/payments.php', 'payments');

        $this->app->bind(Payment::class, function ($app) {
            switch(config('payments.payment_type')){
                //in case of paypal
                case 'paypal' : return new PayPal();
                break;

                //in case of stripe
                case 'stripe' : return new StripePay();
                break;

                //default case
                default : return new PayPal();
            }            
        });

        $this->app->alias(Payment::class, 'Payment');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(){
        return [
            'Payment',
        ];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(){
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/payments.php' => config_path('payments.php'),
        ], 'payments.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/Hetsabit'),
        ], 'payments.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/Hetsabit'),
        ], 'payments.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/Hetsabit'),
        ], 'payments.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
