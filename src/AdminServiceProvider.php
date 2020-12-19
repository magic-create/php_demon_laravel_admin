<?php

namespace Demon\AdminLaravel;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //  加载视图
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'admin');
        //  单例化配置
        $this->app->singleton('admin', function($app) { return new Avatar($app['config']); });
    }
}
