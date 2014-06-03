<?php

/**
* src/MicroCMS/DependencyInjection/ServiceFactory.php
*
* Container Core Service Factory Class
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
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
            $container->getParameter('kernel.log_dir'),
            $container->getParameter('kernel.env')
        );

        return($factory->getServiceClass());
    }
}
