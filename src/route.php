<?php

use Demon\AdminLaravel\example\Controller;
use Demon\AdminLaravel\Middleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Symfony\Component\HttpKernel\Exception\HttpException;

app('router')->group([
    'namespace' => 'App\Admin\Controllers',
    'prefix' => config('admin.path'),
    'middleware' => [StartSession::class, VerifyCsrfToken::class, Middleware::class],
], function($router) {
    //  路由方法
    $func = function($mod = 'common', $con = 'index', $act = 'index') {
        //  如果该路由没有被直接定义
        if (!Route::has($con)) {
            //  指向具体文件
            $controller = "App\\Admin\\Controllers\\" . ucwords($mod) . "\\" . ucwords($con) . 'Controller';
            //  如果文件存在
            try {
                if (class_exists($controller)) {
                    $control = App::make($controller);
                    // 如果方法存在
                    if (method_exists($control, $act))
                        return App::call([$control, $act]);
                    //  如果方法不存在
                    else abort(404);
                }
                //  返回错误页面
                else abort(404);
            } catch (HttpException $exception) {
                $view = 'admin::preset.error.' . $exception->getStatusCode();
                if (!request()->ajax()) {
                    if ($view = view()->exists($view) ? $view : 'admin::preset.error.unknown')
                        return view($view, compact('exception'));
                }
                throw $exception;
            }
        }
        //  重定向到自定义路由
        else return redirect($con);
    };
    //  默认解析
    $router->match(['get', 'post'], '/', function() use ($func) { return $func(); });
    //  授权解析
    $router->match(['get', 'post'], '/auth/{act?}', function($act = 'login') use ($func) { return $func('common', 'auth', $act); });
    //  实例展示
    $router->match(['get', 'post'], '/example/{act?}', function($act = 'index') {
        try {
            $control = App::make(Controller::class);
            if (method_exists($control, $act))
                return App::call([$control, $act]);
            else response(null, 404);
        } catch (HttpException $exception) {
            $view = 'admin::preset.error.' . $exception->getStatusCode();
            if (!request()->ajax()) {
                if ($view = view()->exists($view) ? $view : 'admin::preset.error.unknown')
                    return view($view, compact('exception'));
            }
            throw $exception;
        }
    });
    //  外链图片
    $router->get('/extend/image/url', function() {
        //  获取参数
        $url = request()->get('url');
        if (!$url || filter_var($url, FILTER_VALIDATE_URL) === false)
            return response(null, 404);
        //  Curl请求
        bomber()->curl($url, [], ['method' => 'file'], function($result, $status) use (&$content, &$type) {
            $content = ($status['http_code'] ?? 0) == 200 ? $result : null;
            $type = $status['content_type'];
        });

        //  响应结果
        return response($content, $type ? 200 : 404, ['Content-Type' => $type]);
    });
    //  一级解析
    $router->match(['get', 'post'], '/{mod?}', function($mod) use ($func) { return $func($mod); });
    //  二级解析
    $router->match(['get', 'post'], '/{mod}/{con?}', function($mod, $con) use ($func) { return $func($mod, $con); });
    //  三级解析
    $router->match(['get', 'post'], '/{mod}/{con}/{act?}', function($mod, $con, $act) use ($func) { return $func($mod, $con, $act); });
});
