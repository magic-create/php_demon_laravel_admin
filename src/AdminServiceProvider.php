<?php

namespace Demon\AdminLaravel;

use App\Http\Admin\Exceptions\Handler;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\FileViewFinder;

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
        $this->mergeConfigFrom(__DIR__ . '/config/dbtable.php', 'dbtable');
        //  动态生成最终有效前端资源路径（CDN优先，如果未配置则拼接本地路径）
        config()->set('admin.assets', config('admin.cdn') ? : config('admin.static', '/static/admin') . '/libs');
        //  加载路由
        $this->loadRoutesFrom(__DIR__ . '/route.php');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //  加载视图
        $this->loadViewsFrom([__DIR__ . '/views', admin_path('Views')], 'admin');
        //  单例化配置
        $this->app->singleton('admin', function($app) { return new Admin($app['config']); });
        //  前端资源
        $this->publishes([
            __DIR__ . '/assets' => public_path(trim(config('admin.static', '/static/admin'), '/')),
        ], 'admin');
    }
}
