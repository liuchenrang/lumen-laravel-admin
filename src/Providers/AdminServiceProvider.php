<?php

namespace Encore\Admin\Providers;

use Encore\Admin\Facades\Admin;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        'Encore\Admin\Commands\MakeCommand',
        'Encore\Admin\Commands\MenuCommand',
        'Encore\Admin\Commands\InstallCommand',
        'Encore\Admin\Commands\UninstallCommand',
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin.auth'        => \Encore\Admin\Middleware\Authenticate::class,
        'admin.pjax'        => \Encore\Admin\Middleware\PjaxMiddleware::class,
        'admin.log'         => \Encore\Admin\Middleware\OperationLog::class,
        'admin.permission'  => \Encore\Admin\Middleware\PermissionMiddleware::class,
        'admin.bootstrap'   => \Encore\Admin\Middleware\BootstrapMiddleware::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'admin' => [
            'admin.auth',
            'admin.pjax',
            'admin.log',
            'admin.bootstrap',
        ],
    ];

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app instanceof LumenApplication) {
            $this->app->configure("filesystems");
            app()->bind('filesystem', function(){
                return new FilesystemManager($this->app);
            });
            $this->app->configure("admin");

        }
        $this->loadViewsFrom(__DIR__.'/../../views', 'admin');
        $this->loadTranslationsFrom(__DIR__.'/../../lang/', 'admin');
//        $this->app->configure('hashids');

        $this->publishes([__DIR__.'/../../config/admin.php' => config_path('admin.php')], 'laravel-admin');
        $this->publishes([__DIR__.'/../../assets' => public_path('packages/admin')], 'laravel-admin');

        Admin::registerAuthRoutes();

        if (file_exists($routes = admin_path('routes.php'))) {
            require $routes;
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
         $this->boot();

//        $this->app->booting(function () {
//
//        });
//        $loader = AliasLoader::getInstance();
//
//        $loader->alias('Admin', \Encore\Admin\Facades\Admin::class);

//        if (is_null(config('auth.guards.admin'))) {
            $this->setupAuth();
//        }

        app()->singleton('LaravelUrl', \Illuminate\Routing\UrlGenerator::class);

        $this->registerRouteMiddleware();

        $this->commands($this->commands);
    }

    /**
     * Setup auth configuration.
     *
     * @return void
     */
    protected function setupAuth()
    {
        config([
            'auth.guards.admin.driver'    => 'session',
            'auth.guards.admin.provider'  => 'admin',
            'auth.providers.admin.driver' => 'eloquent',
            'auth.providers.admin.model'  => config('admin.database.users_model'),
        ]);
    }

    /**
     * Register the route middleware.
     *
     * @return void
     */
    protected function registerRouteMiddleware()
    {
        // register route middleware.
//        app()->routeMiddleware($this->routeMiddleware);

//        foreach ($this->routeMiddleware as $key => $middleware) {
//            app('router')->aliasMiddleware($key, $middleware);
//        }
//        app()->routeMiddleware($this->middlewareGroups);

        // register middleware group.
//        foreach ($this->middlewareGroups as $key => $middleware) {
//            app('router')->middlewareGroup($key, $middleware);
//        }
    }
}
