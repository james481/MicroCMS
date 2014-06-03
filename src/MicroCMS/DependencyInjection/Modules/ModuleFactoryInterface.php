<?php

/**
* src/MicroCMS/DependencyInjection/Modules/ModuleFactoryInterface.php
*
* Interface for container service factories.
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
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
