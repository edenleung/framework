<?php
namespace ligth;

use ligth\traits\StaticInstance;

class Route
{
    public static $routes;
    public static $methods;
    public static $callbacks;

    use StaticInstance;

    public static function get($path, $argv)
    {
        $uri = strpos($path, '/') === 0 ? $path : '/' . $path;
        self::$routes[] = $uri;
        self::$methods[] = 'GET';
        self::$callbacks[] = $argv;
    }

    public function dispatch(App $app)
    {
        $path = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $keys = array_keys(self::$routes, $path);
        if (empty($keys)) {
            throw new \Exception('Route: '. $path . ' Not Found');
        }
        foreach ($keys as $key) {
            if ($method == self::$methods[$key]) {
                if (!is_object(self::$callbacks[$key])) {
                    $route = self::$callbacks[$key];
                    $s = explode('/', $route);
                    list($module) = $s;
                    $last = end($s);
                    $segments = explode('@', $last);
                    list($controller, $action) = $segments;
                    $namespace = '\\app\\' . $module . '\\controller\\' . $controller;
                    if (!class_exists($namespace)) 
                    {
                        throw new \Exception("{$namespace} does not exists!");
                    }

                    $app->getContainer()->call([$namespace, $action]);

                } else {
                    call_user_func(self::$callbacks[$key]);
                }
            }
        }
    }
}