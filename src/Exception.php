<?php
namespace ligth;

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

class Exception
{
    static public function reigster()
    {
        $whoops = new Run;
        $whoops->prependHandler(new PrettyPageHandler);
        $whoops->register();
    }
}