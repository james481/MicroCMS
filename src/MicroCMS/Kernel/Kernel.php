<?php

/**
* src/MicroCMS/Kernel/Kernel.php
*
* Application Main Kernel
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
*
*/

namespace MicroCMS\Kernel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use MicroCMS\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class Kernel Extends AbstractKernel
{
    /**
     * getConfigDir
     * Get the global app config directory
     *
     * @return string $configDir
     */
    public function getConfigDir()
    {
        $configDir = $this->getRootDir() . '/app/config/' . $this->env . '/';
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
        $builder = new ContainerBuilder($this->getContainerBuilder());
        $builder->setConfigLocator(new FileLocator($this->getConfigDir()));
        $container = $builder->prepareContainer();
        $container->set('kernel', $this);
        $container->compile();

        return($container);
    }
}
