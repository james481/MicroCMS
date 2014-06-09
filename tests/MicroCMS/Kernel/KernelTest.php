<?php

/**
* tests/MicroCMS/Kernel/KernelTest.php
*
* Test the MicroCMS Main Application Kernel.
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\Kernel;

class KernelTest extends AbstractKernelTest
{
    /**
     * testContainerBuildsConfigLocator
     */
    public function testContainerBuildsConfigLocator()
    {
        $kernel = $this->getTestKernelInstance();
        $kernel->setRootDir($this->getKernelRootDir());
        $kernel->bootstrap();

        $ref = new \ReflectionClass($kernel);
        $container_ref = $ref->getProperty('container');
        $container_ref->setAccessible(true);
        $container = $container_ref->getValue($kernel);

        $this->assertTrue($container->has('kernel.config_locator'));
        $this->assertInstanceOf(
            'Symfony\Component\Config\FileLocator',
            $container->get('kernel.config_locator')
        );
    }

    /**
     * testInvalidConfigDirThrowsException
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidConfigDirThrowsException()
    {
        $kernel = $this->getTestKernelInstance();
        $kernel->setConfigDir(__DIR__ . '/invalid_dir');
    }

    /**
     * getTestKernelInstance
     *
     * @return MicroCMS\Kernel\AbstractKernel $kernel
     */
    protected function getTestKernelInstance()
    {
        return(new Kernel('test'));
    }
}
