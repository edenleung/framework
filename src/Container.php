<?php

namespace ligth;

use DI\ContainerBuilder;

class Container
{
    protected $container;

    public function __construct()
    {
        $builder = new ContainerBuilder();
        $builder->useAutowiring(true);
        $this->container = $builder->build();
    }

    public function getContainer()
    {
        return $this->container;
    }
}