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
        //  合并配置
        $this->mergeConfigFrom(__DIR__ . '/config/admin.php', 'admin');
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
        $this->app->singleton('admin', function($app) { return new Admin($app['config']); });
        //  前端资源
        $this->publishes([__DIR__ . '/assets' => public_path('static/admin')], 'admin');
    }
}
