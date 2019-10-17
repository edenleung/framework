<?php

namespace ligth\traits;

trait StaticInstance
{
    static private $instance;

    public static function instance($params = null)
    {

        if (!self::$instance instanceof self) {
            self::$instance = new self($params);
        }

        return self::$instance;
    }
}