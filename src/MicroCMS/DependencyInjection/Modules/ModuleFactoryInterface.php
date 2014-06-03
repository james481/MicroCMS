<?php

/**
* src/MicroCMS/DependencyInjection/Modules/ModuleFactoryInterface.php
*
* Interface for container service factories.
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
*
*/

namespace MicroCMS\DependencyInjection\Modules;

interface ModuleFactoryInterface
{
    /**
     * getServiceClass
     *
     * @return mixed $class The container service class
     */
    public function getServiceClass();
}
