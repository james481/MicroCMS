<?php

/**
* src/MicroCMS/Kernel/AbstractKernel.php
*
* Application Kernel Abstract
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
*
*/

namespace MicroCMS\Kernel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class AbstractKernel
{
    /**
     * The DI Container
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    protected $container;

    /**
     * The application environment
     * @param string env
     */
    protected $env;

    /**
     * The request object
     * @param Symfony\Component\HttpFoundation\Request $request
     */
    protected $request;

    /**
     * Root application directory
     * @param string rootDir
     */
    protected $rootDir;

    /**
     * Constructor
     *
     * @param string $env Application Environment
     * @return null
     */
    public function __construct($env = 'prod')
    {
        $this->env = $env;
        $this->bootstrap();
    }

    /**
     * getEnvironment
     * Get the application environment
     *
     * @return string $env
     */
    public function getEnvironment()
    {
        return($this->env);
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
     * bootstrap
     * Bootstrap the application and pass the request
     * to handle.
     *
     * @return void
     */
    protected function bootstrap()
    {
        $this->request = Request::createFromGlobals();
        $this->container = $this->buildContainer();
        $response = $this->handle($this->request);
        $response->send();
        $this->terminate();
    }

    /**
     * buildContainer
     * Build the DI container, extended by children kernels.
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
     * The main application handler, extended by children
     * kernels.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    protected function handle(Request $request)
    {
        $response = new Response();
        return($response);
    }

    /**
     * terminate
     * Clean up after the response has been sent.
     *
     * @return void
     */
    protected function terminate()
    {
    }
}
