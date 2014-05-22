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

class Kernel Extends AbstractKernel
{
    /**
     * buildContainer
     * Build the DI container
     *
     * @return Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    protected function buildContainer()
    {
        $container = new ContainerBuilder();
        return($container);
    }

    /**
     * handle
     * The main application handler.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    protected function handle(Request $request)
    {
        $response = new Response($this->getRootDir() . "\n");
        return($response);
    }
}
