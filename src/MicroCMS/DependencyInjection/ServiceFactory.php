<?php

/**
* src/MicroCMS/DependencyInjection/ServiceFactory.php
*
* Container Core Service Factory Class
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
*
*/

namespace MicroCMS\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerInterface;
use MicroCMS\DependencyInjection\Modules\MonologFactory;


class ServiceFactory
{
    /**
     * buildLogger
     * Build the logger service
     *
     * @return Monolog\Logger $logger
     */
    public static function buildLogger(ContainerInterface $container)
    {
        $factory = new MonologFactory(
            $container->getParameter('log_dir'),
            $container->getParameter('kernel.env')
        );

        return($factory->getServiceClass());
    }
}
