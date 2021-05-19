<?php
namespace Laracle\ShopBox;

use Illuminate\Support\ServiceProvider;
use Laracle\ShopBox\Console\InstallApp;
use Laracle\ShopBox\Models\Product;
use Laracle\ShopBox\Library\Cart;
use App\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Session\Middleware\StartSession;

class ShopBoxServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

      /*

      $kernel = $this->app->make(Kernel::class);
     $kernel->pushMiddleware(ShareErrorsFromSession::class);
     $kernel->pushMiddleware(StartSession::class);

     */


        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laracle');


        
        $this->loadViewsFrom(Config('shopbox.shopbox_admin_view_dir'), 'laracle');
        $this->loadMigrationsFrom(__DIR__.'/Database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

      //  $this->app['router']->pushMiddlewareToGroup('web', \Illuminate\Session\Middleware\StartSession::class);
        //$this->app['router']->pushMiddlewareToGroup('web', \Illuminate\View\Middleware\ShareErrorsFromSession::class);




       //dump($kernel);

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
        $this->mergeConfigFrom(__DIR__.'/../config/shopbox.php', 'shopbox');

        // Register the service the package provides.
        $this->app->singleton('shopbox', function ($app) {
            return new ShopBox;
        });

        $this->app->singleton('cart', function ($app) {
            return new Cart;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['shopbox'];
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
            __DIR__.'/../config/shopbox.php' => config_path('shopbox.php'),
        ], 'shopbox.config');

        /*

        $this->publishes([
            __DIR__.'/Database/Seeders' => base_path('database/seeders'),
        ], 'seeder');

        $this->publishes([
            __DIR__.'/Database/Factories' => base_path('database/factories'),
        ], 'factory');
        */

        //php artisan vendor:publish --tag=shopbox.admin_ui --force

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/laracle'),
        ], 'shopbox.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/laracle'),
        ], 'shopbox.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/laracle'),
        ], 'shopbox.views');*/

        // Registering package commands.
        $this->commands([
          InstallApp::class
        ]);
    }
}
