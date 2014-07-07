<?php

/**
* src/MicroCMS/Routing/RouterBuilder.php
*
* Builds the MicroCMS application router
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\Routing;

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use MicroCMS\Template\Resolver;
use MicroCMS\Routing\Matcher\DefaultMatcher;
use MicroCMS\Routing\Matcher\TemplateMatcher;

class RouterBuilder
{
    use \MicroCMS\DependencyInjection\LogAwareTrait;

    /**
     * RequestContext object
     * @param Symfony\Component\Routing\RequestContext $context
     */
    protected $context;

    /**
     * The Kernel Environment
     * @param string kernelEnv
     */
    protected $kernelEnv = 'prod';

    /**
     * The FileLocator for reading routing config
     * @param Symfony\Component\Config\FileLocatorInterface $locator
     */
    protected $locator;

    /**
     * Template Resolver
     * @param MicroCMS\Template\Resolver $resolver
     */
    protected $resolver;

    /**
     * Constructor
     *
     * @param MicroCMS\Template\Resolver $resolver
     * @param Symfony\Component\Config\FileLocatorInterface $config_locator
     * @param string $kernel_env
     * @return null
     */
    public function __construct(
        Resolver $resolver,
        FileLocatorInterface $config_locator = null,
        $kernel_env = 'prod'
    ) {
        $this->resolver = $resolver;
        $this->locator = $config_locator;
        $this->kernelEnv = $kernel_env;
    }

    /**
     * prepareRouter
     * Build the route matchers and router.
     *
     * @return MicroCMS\Routing\Router $router
     */
    public function prepareRouter()
    {
        $router = new Router();

        // Inject Logger if we have one
        if ($this->logger) {
            $router->setLogger($this->logger);
        }

        // Build the matcher stack
        $router->addMatcher($this->buildDefaultMatcher());

        if ($this->hasRoutingTemplates()) {
            $router->addMatcher($this->buildTemplateMatcher());
        }

        if ($this->hasRoutingConfig()) {
            $router->addMatcher($this->buildSymfonyMatcher());
        }

        return($router);
    }

    /**
     * buildDefaultMatcher
     * Build the matcher that will return default routes or 404
     *
     * @return MicroCMS\Routing\Matcher\DefaultMatcher $matcher
     */
    protected function buildDefaultMatcher()
    {
        $matcher = new DefaultMatcher($this->resolver, $this->getRequestContext());
        return($matcher);
    }

    /**
     * buildSymfonyMatcher
     * Build a Symfony matcher for matching yaml configured
     * routes.
     *
     * @return Symfony\Component\Routing\Matcher\UrlMatcher $matcher
     */
    protected function buildSymfonyMatcher()
    {
        if (!$this->locator) {
            throw new \InvalidArgumentException('Unable to build Symfony matcher without config locator.');
        }

        $yaml_loader = new YamlFileLoader($this->locator);
        $routes = $yaml_loader->load($this->getRoutingFilename());
        $matcher = new UrlMatcher($routes, $this->getRequestContext());

        return($matcher);
    }

    /**
     * buildTemplateMatcher
     * Build the matcher that will match template files directly to
     * routes.
     *
     * @return MicroCMS\Routing\Matcher\TemplateMatcher $matcher
     */
    protected function buildTemplateMatcher()
    {
        $matcher = new TemplateMatcher($this->resolver, $this->getRequestContext());

        return($matcher);
    }

    /**
     * getRequestContext
     * Get blank RequestContext.
     *
     * @return Symfony\Component\Routing\RequestContext $context
     */
    protected function getRequestContext()
    {
        if (null === $this->context) {
            $this->context = new RequestContext();
        }

        return($this->context);
    }

    /**
     * hasRoutingConfig
     * Check if the routing file exists and needs to be parsed.
     *
     * @return bool $has_config
     */
    protected function hasRoutingConfig()
    {
        $has_config = false;

        if ($this->locator) {
            try {
                $routing_file = $this->locator->locate($this->getRoutingFilename());

                if (filesize($routing_file) > 0) {
                    $this->debug(sprintf('hasRoutingConfig: Located Routing File %s', $routing_file));
                    $has_config = true;
                }
            } catch (\InvalidArgumentException $e) {}
        }

        return($has_config);
    }

    /**
     * hasRoutingTemplates
     * Check if the templates directory has any templates
     * we want to route directly to.
     *
     * @return bool $template_found
     */
    protected function hasRoutingTemplates()
    {
        return($this->resolver->hasRoutableTemplates());
    }

    /**
     * getRoutingFilename
     *
     * @return string $routing_file
     */
    private function getRoutingFilename()
    {
        $routing_file = 'routing_' . $this->kernelEnv . '.yml';

        return($routing_file);
    }
}
