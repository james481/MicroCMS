<?php

/**
* tests/MicroCMS/DependencyInjection/ContainerBuilderTest.php
*
* MicroCMS Container Builder Test
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
use Symfony\Component\Config\FileLocator;

class ContainerBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testCoreConfigFileLoads
     */
    public function testCoreConfigFileLoads()
    {
        // Build container
        $container = new SymContainerBuilder();
        $container->setParameter('kernel.env', 'test');
        $root_dir = __DIR__ . '../Kernel/Fixtures/';
        $container->setParameter('kernel.root_dir', $root_dir);

        // Build config locator
        $config_dir = __DIR__ . '/../Kernel/Fixtures/app/config/';
        $locator = new FileLocator($config_dir);

        // Make sure the builder reads the config
        $builder = new ContainerBuilder($container);
        $builder->setConfigLocator($locator);

        $container = $builder->prepareContainer();
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\ContainerBuilder', $container);
        $container->compile();

        $this->assertTrue($container->hasParameter('kernel.log_dir'));
        $this->assertEquals($root_dir . '/app/logs/', $container->getParameter('kernel.log_dir'));
    }

    /**
     * testAppConfigFileLoads
     */
    public function testAppConfigFileLoads()
    {
        // Builder defaults to 'prod' env, so no need to set it

        // Build config locator
        $config_dir = __DIR__ . '/../Kernel/Fixtures/app/config/';
        $locator = new FileLocator($config_dir);

        // Make sure the builder reads the app config
        $builder = new ContainerBuilder($container);
        $builder->setConfigLocator($locator);

        $container = $builder->prepareContainer();
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\ContainerBuilder', $container);
        $this->assertTrue($container->hasParameter('app.test_param'));
        $this->assertEquals('test_param', $container->getParameter('app.test_param'));
    }

    /**
     * testConfigLocatorRequired
     * @expectedException \RuntimeException
     */
    public function testConfigLocatorRequired()
    {
        $builder = new ContainerBuilder($container);
        $container = $builder->prepareContainer();
    }

    /**
     * testConfigFileRequired
     * Test that a non-existant config file throws an exception,
     * this is done by setting a bogus kernel env.
     * @expectedException MicroCMS\DependencyInjection\Exception\ConfigFileException
     */
    public function testConfigFileRequired()
    {
        // Build container
        $container = new SymContainerBuilder();
        $container->setParameter('kernel.env', 'foo');

        // Build config locator
        $config_dir = __DIR__ . '/../Kernel/Fixtures/app/config/';
        $locator = new FileLocator($config_dir);

        // Make sure the builder reads the config
        $builder = new ContainerBuilder($container);
        $builder->setConfigLocator($locator);

        $container = $builder->prepareContainer();
    }
}
