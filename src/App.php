<?php

namespace ligth;

use DI\ContainerBuilder;

class App extends Container
{
    use \ligth\traits\StaticInstance;

    static public function run()
    {
        ini_set('display_errors', 1);

        // Whoops 注册异常错误处理
        Exception::reigster();

        return App::instance();
    }

    public function dispatch()
    {
        // 加载路由配置
        $routePath = ROOT_PATH . DIRECTORY_SEPARATOR . 'route' . DIRECTORY_SEPARATOR;
        $files = scandir($routePath);

        foreach($files as $file) {
            if (strpos($file, '.php'))
            {
                include $routePath . $file;
            } 
        }
        
        // 路由调度
        $this->container->call([Route::class, 'dispatch']);
    }
}
