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

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use MicroCMS\DependencyInjection\ContainerBuilder;
use MicroCMS\Routing\RouterBuilder;

class Kernel Extends AbstractKernel
{
    const VERSION = '0.1.0';

    /**
     * The app configuration directory
     * @param string configDir
     */
    protected $configDir;

    /**
     * The system (core) config directory
     * @param string systemConfigDir
     */
    protected $systemConfigDir;

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
            $this->rootDir = str_replace('vendor/james481/micro-cms/src/MicroCMS/Kernel', '', $rootDir);
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

        try {
            // Route Request
            $req_info = $this->router->matchRequest($request);

            // Resolve Controller
            $resolver = new Resolver();
            $controller = $resolver->resolveController($req_info['_controller']);

            if (false === $controller) {
                throw new Exception(sprintf('Invalid Controller %s From Route %s', $req_info['_controller'], $req_info['_route']));
            }

            // If the controller is ContainerAware, inject the container
            if ($controller[0] instanceof ContainerAwareInterface) {
                $controller[0]->setContainer($this->container);
            }

            // Build controller method arguments
            $arguments = $resolver->resolveArguments($controller, $request, $req_info);

            // Call controller and get response
            $response = call_user_func_array($controller, $arguments);

            if (!$response instanceof Response) {
                throw new Exception(sprintf('Controller %s did not return a valid Response object.', $req_info['_controller']));
            }

        } catch (\Exception $e) {
            $response = $this->handleException($e);
        }

        return($response);
    }

    /**
     * handleException
     *
     * @param Exception $exception
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function handleException(\Exception $exception)
    {
        $response = null;
        $template_controller = '\\MicroCMS\\Controller\\TemplateController';
        $error_controller = '\\MicroCMS\\Controller\\ErrorController';

        // If production (kernel.env = 'prod') we'll attempt
        // to display the 500 template if it exists, otherwise
        // we'll call the last ditch method.
        if ('prod' === $this->container->getParameter('kernel.env')) {
            try {
                $template = $this->container->get('template_resolver')->resolveErrorTemplate();

                if ((false !== $template) && class_exists($template_controller)) {
                    $controller = new $template_controller();
                    $controller->setContainer($this->container);
                    $callable = array($controller, 'indexAction');
                    $response = call_user_func_array($callable, array($template));
                } elseif (class_exists($error_controller)) {
                    $callable = array(new $error_controller(), 'errorAction');
                    $response = call_user_func_array($callable, array());
                }
            } catch (\Exception $e) {
                $response = null;
            }
        } else {
            // Call our pretty stack trace generator for dev
            try {
                if (class_exists($error_controller)) {
                    $callable = array(new $error_controller(), 'displayTraceAction');
                    $response = call_user_func_array($callable, array($exception));
                }
            } catch (\Exception $e) {
                $response = null;
            }
        }

        if (!$response) {
            $response = new Response('Error - 500');
        }

        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

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
     * setSystemConfigDir
     * Sets the system (core) config directory
     * (This is typically uneeded except for tests)
     *
     * @param string $configDir
     * @return self $this
     */
    public function setSystemConfigDir($configDir)
    {
        if (!is_dir($configDir)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid System Configuration Directory: %s', $configDir)
            );
        }

        $this->systemConfigDirectory = $configDir;

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
        $config_dirs = array($this->getSystemConfigDir(), $this->getConfigDir());
        $config_locator = new FileLocator($config_dirs);
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
     * Build the router for matching requests to controllers.
     *
     * @return Symfony\Component\Router\Matcher\RequestMatcherInterface $router
     */
    protected function buildRouter()
    {
        // Get the MicroCMS router builder, and build the router.
        $builder = new RouterBuilder(
            $this->container->get('template_resolver'),
            $this->container->get('kernel.config_locator'),
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

    /**
     * getSystemConfigDir
     * Get the system configuration directory.
     *
     * @return string $systemConfigDir
     */
    protected function getSystemConfigDir()
    {
        if (null === $this->systemConfigDir) {
            $reflector = new \ReflectionClass($this);
            $rootDir = dirname($reflector->getFileName());
            $this->systemConfigDir = $rootDir . '/Resources/config/';
        }

        return($this->systemConfigDir);
    }
}
