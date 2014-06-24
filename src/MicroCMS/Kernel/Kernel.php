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
use Symfony\Component\Config\FileLocator;
use MicroCMS\DependencyInjection\ContainerBuilder;
use MicroCMS\Routing\RouterBuilder;

class Kernel Extends AbstractKernel
{
    const VERSION = '0.0.1';

    /**
     * The app configuration directory
     * @param string configDir
     */
    protected $configDir;

    /**
     * getConfigDir
     * Get the global app config directory
     *
     * @return string $configDir
     */
    public function getConfigDir()
    {
        if (null === $this->configDir) {
            $this->configDir = $this->getRootDir() . '/app/config/';
        }

        return($this->configDir);
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

        try {
            // Route Request
            $req_info = $this->router->match($request->getPathInfo());

            // Resolve Controller
            $resolver = new Resolver();
            $controller = $resolver->resolveController($req_info['_controller']);

            if (false === $controller) {
                throw new Exception(sprintf('Invalid Controller %s From Route %s', $req_info['_controller'], $req_info['_route']));
            }

            $arguments = $resolver->resolveArguments($controller, $request);

            $response = call_user_func_array($controller, $arguments);

        } catch (\Exception $e) {
            // TODO: Handle exceptions based on env (show trace, etc)
            $response = new Response(sprintf('Request Error: %s', $e->getMessage()));
        }

        return($response);
    }

    /**
     * setConfigDir
     * Sets the application config directory
     * (This is typically uneeded except for tests)
     *
     * @param string $configDir
     * @return self $this
     */
    public function setConfigDir($configDir)
    {
        if (!is_dir($configDir)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid Application Configuration Directory: %s', $configDir)
            );
        }

        $this->configDir = $configDir;

        return($this);
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
        $config_locator = new FileLocator($this->getConfigDir());
        $builder->setConfigLocator($config_locator);

        // Finish container
        $container = $builder->prepareContainer();
        $container->set('kernel', $this);
        $container->set('kernel.config_locator', $config_locator);
        $container->compile();

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
        // Get the MicroCMS router builder, and build the router.
        $builder = new RouterBuilder(
            $this->container->get('kernel.config_locator'),
            $this->container->getParameter('kernel.template_dir'),
            $this->container->getParameter('kernel.env')
        );

        // Inject logger
        if ($this->container->has('logger')) {
            $builder->setLogger($this->container->get('logger'));
        }

        // Finish Router
        $router = $builder->prepareRouter();

        return($router);
    }
}
