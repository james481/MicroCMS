<?php

/**
* src/MicroCMS/DependencyInjection/ContainerBuilder.php
*
* Dependency Injection Container Builder
* Builds a Symfony container with the framework dependencies.
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
*
*/

namespace MicroCMS\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder as Builder;
use Symfony\Component\Yaml\Yaml;


class ContainerBuilder extends Builder
{
    /**
     * Constructor
     *
     * @param mixed ConfigInterface $fwConfig
     * @return null
     */
    public function __construct()
    {
    }


}
