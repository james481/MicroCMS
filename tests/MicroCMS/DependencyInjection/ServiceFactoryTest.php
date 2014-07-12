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

    /**
     * testBuildTemplateResolver
     */
    public function testBuildTemplateResolver()
    {
        // Build container
        $container = new SymContainerBuilder();
        $container->setParameter('kernel.env', 'test');
        $template_dir = __DIR__ . '/../Routing/Fixtures/templates/';
        $container->setParameter('kernel.template_dir', $template_dir);

        // Build Template Resolver
        $resolver = ServiceFactory::buildTemplateResolver($container);
        $this->assertInstanceOf('MicroCMS\Template\Resolver', $resolver);

        // Resolve a template
        $template = $resolver->resolveTemplate('/test.html');
        $this->assertInstanceOf('MicroCMS\Template\Template', $template);
        $this->assertEquals('test.html', $template->getRenderName());
    }

    /**
     * testBuildTemplating
     */
    public function testBuildTemplating()
    {
        // Build a 'dev' instance of Twig (without caching)

        // Build container
        $container = new SymContainerBuilder();
        $container->setParameter('kernel.env', 'dev');
        $template_dir = __DIR__ . '/../Routing/Fixtures/templates/';
        $container->setParameter('kernel.template_dir', $template_dir);
        $container->setParameter('kernel.cache_dir', null);

        // Build Template engine
        $twig = ServiceFactory::buildTemplating($container);
        $this->assertInstanceOf('Twig_Environment', $twig);

        // Should be debug
        $this->assertTrue($twig->isDebug());

        // Test a render
        $result = $twig->render('index.html', array());
        $this->assertEquals("index.html\n", $result);
    }

}
