<?php

/**
* src/MicroCMS/Kernel/AbstractKernel.php
*
* Application Kernel Abstract
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
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

abstract class AbstractKernel
{
    /**
     * Is the application booted and ready to handle request
     * @param bool $booted
     */
    protected $booted = false;

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
     * The request route matcher.
     * @param Symfony\Component\Router\Matcher\RequestMatcherInterface $router
     */
    protected $router;

    /**
     * Constructor
     *
     * @param string $env Application Environment
     * @return null
     */
    public function __construct($env = 'prod')
    {
        $this->env = $env;
    }

    /**
     * bootstrap
     * Bootstrap the application
     *
     * @return void
     */
    public function bootstrap()
    {
        if (!$this->booted) {
            $this->container = $this->buildContainer();
            $this->router = $this->buildRouter();
            $this->booted = true;
        }
    }

    /**
     * getBooted
     * Is the application booted?
     *
     * @return bool $booted
     */
    public function getBooted()
    {
        return($this->booted);
    }

    /**
     * getRootDir
     * Gets the application root directory
     *
     * @return string $rootDir
     */
    public function getRootDir()
    {
        return($this->rootDir);
    }

    /**
     * handle
     * The main application handler, extended by children
     * kernels.
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
        $response = new Response();
        return($response);
    }

    /**
     * setRootDir
     * Sets the application root directory
     * (This is typically unneeded except for tests)
     *
     * @param string $rootDir
     * @return self $this
     */
    public function setRootDir($rootDir)
    {
        if (!is_dir($rootDir)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid Application Configuration Directory: %s', $rootDir)
            );
        }

        $this->rootDir = $rootDir;
    }

    /**
     * terminate
     * Clean up after the response has been sent.
     *
     * @return void
     */
    public function terminate()
    {
    }

    /**
     * buildContainer
     * Build the DI container, extended by children kernels.
     *
     * @return Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    protected function buildContainer()
    {
        $container = $this->getContainerBuilder();
        $container->set('kernel', $this);
        return($container);
    }

    /**
     * buildRouter
     * Build the router for matching requests to controllers,
     * extended by children kernels.
     *
     * @return Symfony\Component\Router\Matcher\RequestMatcherInterface $router
     */
    protected function buildRouter()
    {
        $routes = new RouteCollection();
        $context = new RequestContext();
        $router = new UrlMatcher($routes, $context);

        return($router);
    }

    /**
     * getContainerBuilder
     * Get the Container Builder Class
     *
     * @return Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    protected function getContainerBuilder()
    {
        $kernel_params = new ParameterBag($this->getKernelParams());
        $container = new ContainerBuilder($kernel_params);

        return($container);
    }

    /**
     * getKernelParams
     *
     * @return array $kernel_params
     */
    protected function getKernelParams()
    {
        $kernel_params = array(
            'kernel.root_dir' => $this->getRootDir(),
            'kernel.env' => $this->env,
        );

        return($kernel_params);
    }
}
