<?php
namespace ligth;

use ligth\traits\StaticInstance;

class Route
{
    public static $routes;
    public static $methods;
    public static $callbacks;

    private static $allowMethods = ['GET', 'POST', 'DELETE', 'PUT'];

    use StaticInstance;

    public static function __callStatic($method, $argv)
    {
        $method = strtoupper($method);
        if (in_array($method, self::$allowMethods)) {
            list($path, $argv) = $argv;
            $uri = strpos($path, '/') === 0 ? $path : '/' . $path;
            self::$routes[] = $uri;
            self::$methods[] = $method;
            self::$callbacks[] = $argv;
        }
    }

    public function dispatch(App $app, Request $request)
    {
        $path = $request->getUri();
        $method = $request->getMethod();
        if (strpos($path, '?')) {
            list($path, $query) = explode('?', $path);
        }
        
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
                    $app->getContainer()->call(self::$callbacks[$key]);
                }
            }
        }
    }
}