<?php

namespace Demon\AdminLaravel;

use App\Http\Admin\Exceptions\Handler;
use Demon\AdminLaravel\console\ResetCommand;
use Demon\AdminLaravel\console\TableCommand;
use Illuminate\Console\Command;
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
        $this->mergeConfigFrom(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'admin.php', 'admin');
        $this->mergeConfigFrom(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'dbtable.php', 'dbtable');
        //  动态生成最终有效前端资源路径（CDN优先，如果未配置则拼接本地路径）
        config()->set('admin.assets', (config('admin.cdn') ? : config('admin.static', '/static/admin') . '/libs') . '/');
        //  加载路由
        $this->loadRoutesFrom(__DIR__ . DIRECTORY_SEPARATOR . 'route.php');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //  加载语言包
        $langPath = admin_path('Lang');
        $localePath = $langPath . DIRECTORY_SEPARATOR . $this->app->getLocale();
        $localePreset = __DIR__ . DIRECTORY_SEPARATOR . 'directory' . DIRECTORY_SEPARATOR . 'Lang';
        $this->loadTranslationsFrom(is_dir($localePath) && bomber()->dirList($localePath) ? $langPath : $localePreset, 'admin');
        //  过滤无效的语言
        $locales = config('admin.locales', []);
        foreach ($locales as $locale => $name) {
            $localePath = $langPath . DIRECTORY_SEPARATOR . $locale;
            if (!(is_dir($localePath) && bomber()->dirList($localePath)) && !is_dir($localePreset . DIRECTORY_SEPARATOR . $locale))
                unset($locales[$locale]);
        }
        config()->set('admin.locales', $locales);
        //  检查是否正确安装
        if (!is_dir(admin_path('Controllers')) && !$this->app->runningInConsole())
            throw new \ErrorException('Please install it (AdminService) correctly, Read the README.md first', DEMON_CODE_SERVICE);
        //  加载视图
        $this->loadViewsFrom([__DIR__ . DIRECTORY_SEPARATOR . 'views', admin_path('Views')], 'admin');
        //  实例化更多
        $this->app->singleton('admin', function($app) { return new Admin($app['config']); });
        //  前端资源
        $this->publishes([
            __DIR__ . DIRECTORY_SEPARATOR . 'directory' => admin_path(),
            __DIR__ . DIRECTORY_SEPARATOR . 'assets' => public_path(trim(config('admin.static', '/static/admin'), '/')),
        ], 'admin');
        //  注册命令
        $this->app->singleton('common.admin.table', function($app) { return new TableCommand($app['files'], $app['composer']); });
        $this->app->singleton('common.admin.reset', function($app) { return new ResetCommand(); });
        $this->commands(['AdminTable' => 'common.admin.table', 'AdminReset' => 'common.admin.reset']);
    }
}
