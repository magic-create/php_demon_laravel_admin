<?php

use Demon\AdminLaravel\access\middleware\SessionPre;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Demon\AdminLaravel\access\middleware\SessionPost;
use Demon\AdminLaravel\access\middleware\LogSave;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

app('router')->group([
    'namespace' => 'App\Admin\Controllers',
    'prefix' => config('admin.path'),
    'middleware' => array_merge([SessionPre::class, StartSession::class, VerifyCsrfToken::class, SessionPost::class], config('admin.middleware', []), [LogSave::class]),
], function($router) {
    //  路由方法
    $run = function($controller = null, $act = 'index', $mod = 'common', $con = 'index') use ($router) {
        //  如果该路由没有被直接定义
        if (!Route::has($con)) {
            //  指向具体文件
            $controller = $controller ? : "App\\Admin\\Controllers\\" . ucwords($mod) . "\\" . ucwords($con) . 'Controller';
            try {
                //  如果文件存在
                if (class_exists($controller)) {
                    //  手动设置路由
                    $router->current()->uses("{$controller}@{$act}");

                    //  运行返回
                    return $router->current()->run();
                }
                else abort(DEMON_CODE_NONE);
            } catch (NotFoundHttpException $exception) {
                if (!request()->ajax() && !config('app.debug'))
                    return admin_view('preset.error.general', ['code' => $exception->getStatusCode(), 'message' => $exception->getMessage()]);
                throw $exception;
            } catch (HttpException $exception) {
                if (!request()->ajax() && !config('app.debug'))
                    return admin_view('preset.error.general', ['code' => $exception->getStatusCode(), 'message' => $exception->getMessage()]);
                throw $exception;
            } catch (ErrorException $exception) {
                if (!request()->ajax() && !config('app.debug'))
                    return admin_view('preset.error.general', ['code' => DEMON_CODE_SERVER, 'message' => config('app.debug') ? $exception->getMessage() : admin_error(DEMON_CODE_SERVER)]);
                throw $exception;
            }
        }
        //  重定向到自定义路由
        else return redirect($con);
    };
    //  首页
    $router->match(['get', 'post'], '/', function() use ($run) { return $run(); });
    //  授权
    $router->match(['get', 'post'], '/auth/{act?}', function($act = 'login') use ($run) { return $run($act == 'login' ? config('admin.authentication') : config('admin.setting'), $act); });
    //  权限
    $router->match(['get', 'post'], '/admin/access/{con?}/{act?}', function($con, $act = 'index') use ($run) { return $run("Demon\\AdminLaravel\\access\\controller\\" . ucwords($con) . 'Controller', $act); });
    //  例子
    $router->match(['get', 'post'], '/example/{act?}', function($act = 'index') use ($run) { return $run(\Demon\AdminLaravel\example\Controller::class, $act); });
    //  扩展图片例子
    $router->match(['get', 'post'], '/extend/image/{act?}', function($act = '') use ($run) { return $run(\Demon\AdminLaravel\example\Extend::class, $act); });
    //  加载自定义
    if (is_file(admin_path('route.php')))
        include admin_path('route.php');
    else include 'directory/route.php';
    //  其他通用
    $router->any('{path}', function($path) use ($run) {
        $path = explode('/', $path);

        return $run(null, $path[2] ?? null, $path[0] ?? null, $path[1] ?? null);
    })->where('path', '.+');
});
