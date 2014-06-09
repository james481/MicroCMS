<?php

/**
* tests/MicroCMS/DependencyInjection/ServiceFactoryTest.php
*
* MicroCMS Container Service Factory Tests
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder as SymContainerBuilder;

class ServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testBuildLogger
     */
    public function testBuildLogger()
    {
        // Build container
        $container = new SymContainerBuilder();
        $container->setParameter('kernel.env', 'test');
        $log_dir = __DIR__ . '/../Kernel/Fixtures/app/logs/';
        $container->setParameter('kernel.log_dir', $log_dir);

        // Build logger
        $logger = ServiceFactory::buildLogger($container);
        $this->assertInstanceOf('Monolog\Logger', $logger);
    }

}
