# PHP Demon Admin Library For Laravel

## 服务说明

本服务仅支持Laravel6+

正常来说是内部开发使用的，外部使用也可以（水平有限，请慎用，可能会有漏洞或者性能问题）

## 安装说明

使用composer安装服务，在config/app.php的providers中增加本服务，设置admin的数据库连接（默认是admin），执行命令生成建表迁移文件，执行迁移动作生成对应表和初始数据，设置静态资源文件目录（不用写public），发布资源到对应目录，设置访问路径（默认是admin），设置静态资源CDN（默认没有，只读本地），大功告成

1. composer require comingdemon/admin-laravel
2. edit config/app.php providers add Demon\AdminLaravel\AdminServiceProvider::class
3. edit.env (ADMIN_CONNECTION) or add config/admin.php (edit connection, default : admin)
4. php artisan admin:table
5. php artisan migrate
6. edit.env (ADMIN_STATIC) or add config/admin.php (edit static, default : /static/admin)
7. php artisan vendor:publish --tag=admin
8. edit.env (ADMIN_PATH) or add config/admin.php (edit path, default : admin)
9. edit.env (ADMIN_CDN) or add config/admin.php (edit cdn, default : )
10. browser url  {address}/admin or {path}

## 进阶操作

1. config('admin.access') = env('ADMIN_ACCESS', false) //开启权限校验（需要数据库支持，默认账号：admin，默认密码：demon）
2. config('admin.authentication') = env('ADMIN_AUTHENTICATION') //自定义授权控制器
3. config('admin.badge') = env('ADMIN_BADGE') //自定义菜单标记统计的类
4. config('admin.session.\*') = env('ADMIN_SESSION_\*') //设置session配置（和laravel自带的session一致）
5. config('admin.element.\*') = env('ADMIN_ELEMENT_\*') //设置页面元素对应的视图

## 特殊申明

本库已发布至Composer，理论上只内部使用，如有问题请自行解决，概不提供服务

最终解释权归魔网天创信息科技:尘兵所属

