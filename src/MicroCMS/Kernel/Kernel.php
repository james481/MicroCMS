<?php

/**
* src/MicroCMS/Kernel/Kernel.php
*
* Application Main Kernel
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*
*/

namespace MicroCMS\Kernel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use MicroCMS\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class Kernel Extends AbstractKernel
{
    const VERSION = '0.0.1';

    /**
     * getConfigDir
     * Get the global app config directory
     *
     * @return string $configDir
     */
    public function getConfigDir()
    {
        $configDir = $this->getRootDir() . '/app/config/';
        return($configDir);
    }

    /**
     * getRootDir
     * Gets the application root directory
     *
     * @return string $rootDir
     */
    public function getRootDir()
    {
        if (null === $this->rootDir) {
            $reflector = new \ReflectionClass($this);
            $rootDir = dirname($reflector->getFileName());
            $this->rootDir = str_replace('src/MicroCMS/Kernel', '', $rootDir);
        }

        return($this->rootDir);
    }

    /**
     * handle
     * The main application handler.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function handle(Request $request)
    {
        if (!$this->booted) {
            $this->bootstrap();
        }

        $this->request = $request;
        $response = new Response(print_r($this->container, true));
        return($response);
    }

    /**
     * buildContainer
     * Build the MicroCMS specific DI container
     *
     * @return Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    protected function buildContainer()
    {

        // Get MicroCMS container builder
        $builder = new ContainerBuilder($this->getContainerBuilder());

        // Set file loader for config files
        $builder->setConfigLocator(new FileLocator($this->getConfigDir()));

        // Finish container
        $container = $builder->prepareContainer();
        $container->set('kernel', $this);
        $container->compile();

        return($container);
    }
}
