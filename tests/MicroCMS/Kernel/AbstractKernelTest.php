<?php

/**
* tests/MicroCMS/Kernel/AbstractKernelTest.php
*
* Test the MicroCMS AbstractKernel.
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\Kernel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class AbstractKernelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testKernelBootState
     */
    public function testKernelBootState()
    {
        $kernel = $this->getTestKernelInstance();
        $kernel->setRootDir($this->getKernelRootDir());
        $this->assertFalse($kernel->getBooted());
        $kernel->bootstrap($this->getEmptyRequest());
        $this->assertTrue($kernel->getBooted());
        $this->assertEquals($this->getKernelRootDir(), $kernel->getRootDir());
    }

    /**
     * testKernelBuildsContainer
     * @return Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function testKernelBuildsContainer()
    {
        $kernel = $this->getTestKernelInstance();
        $kernel->setRootDir($this->getKernelRootDir());
        $this->assertFalse($kernel->getBooted());
        $kernel->bootstrap($this->getEmptyRequest());
        $this->assertTrue($kernel->getBooted());

        $ref = new \ReflectionClass($kernel);
        $this->assertTrue($ref->hasProperty('container'));
        $container_ref = $ref->getProperty('container');
        $container_ref->setAccessible(true);
        $container = $container_ref->getValue($kernel);

        $this->assertEquals('Symfony\Component\DependencyInjection\ContainerBuilder', get_class($container));

        return($container);
    }

    /**
     * testContainerKernelParams
     * @depends testKernelBuildsContainer
     */
    public function testContainerKernelParams($container)
    {
        $this->assertTrue($container->hasParameter('kernel.root_dir'));
        $this->assertEquals($this->getKernelRootDir(), $container->getParameter('kernel.root_dir'));

        $this->assertTrue($container->hasParameter('kernel.env'));
        $this->assertEquals('test', $container->getParameter('kernel.env'));

        $this->assertTrue($container->has('kernel'));
        $this->assertTrue($container->get('kernel') instanceof AbstractKernel);
    }

    /**
     * testKernelBuildsRouter
     */
    public function testKernelBuildsRouter()
    {
        $kernel = $this->getTestKernelInstance();
        $kernel->setRootDir($this->getKernelRootDir());
        $this->assertFalse($kernel->getBooted());
        $kernel->bootstrap($this->getEmptyRequest());
        $this->assertTrue($kernel->getBooted());

        $ref = new \ReflectionClass($kernel);
        $this->assertTrue($ref->hasProperty('router'));
        $router_ref = $ref->getProperty('router');
        $router_ref->setAccessible(true);
        $router = $router_ref->getValue($kernel);

        $this->assertTrue($router instanceof UrlMatcherInterface);
    }

    /**
     * getEmptyRequest
     *
     * @return Symfony\Component\HttpFoundation\Request $request
     */
    protected function getEmptyRequest()
    {
        $request = new Request();
        return($request);
    }

    /**
     * getKernelRootDir
     *
     * @return string $root_dir
     */
    protected function getKernelRootDir()
    {
        $root_dir = __DIR__ . '/Fixtures/';
        return($root_dir);
    }

    /**
     * getTestKernelInstance
     *
     * @return MicroCMS\Kernel\AbstractKernel $kernel
     */
    protected function getTestKernelInstance()
    {
        return(new AbstractKernelInstance('test'));
    }
}

class AbstractKernelInstance extends AbstractKernel {}
