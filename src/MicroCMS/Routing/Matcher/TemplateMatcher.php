<?php

/**
* src/MicroCMS/Routing/Matcher/TemplateMatcher.php
*
* A route matcher for matching requests to twig templates
* in the system templates directory.
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\Routing\Matcher;

use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class TemplateMatcher implements UrlMatcherInterface
{
    /**
     * RequestContext object
     * @param Symfony\Component\Routing\RequestContext $context
     */
    protected $context;

    /**
     * Request object
     * @param Symfony\Component\HttpFoundation\Request $request
     */
    protected $request;

    /**
     * Collection of routes
     * @param Symfony\Component\Routing\RouteCollection $routes
     */
    protected $routes;

    /**
     * The routable templates directory
     * @param string templatePath
     */
    protected $templatePath;

    /**
     * Constructor
     *
     * @param mixed $template_path
     * @param mixed RequestContext $context
     * @return null
     */
    public function __construct($template_path, RequestContext $context)
    {
        $this->templatePath = $template_path;
        $this->context = $context;

        // Build routes from templates
        $this->buildRoutes();
    }

    /**
     * GetContext
     * Gets the request context.
     *
     * @return RequestContext The context
     */
    public function getContext()
    {
        return($this->context);
    }

    /**
     * match
     * Match a request URL to a route.
     *
     * @param string $pathinfo The path info to be parsed
     * @return array An array of parameters
     *
     * @throws ResourceNotFoundException If the resource could not be found
     * @throws MethodNotAllowedException If the resource was found but the request method is not allowed
     */
    public function match($pathinfo)
    {
        return(array());
    }

    /**
     * setContext
     * Sets the request context.
     *
     * @param RequestContext $context The context
     * @return void
     */
    public function setContext(RequestContext $context)
    {
        $this->context = $context;
    }

    /**
     * buildRoutes
     * Build the collection of available routes from
     * the templates in the templates directory.
     *
     * @return void
     */
    protected function buildRoutes()
    {
        if (!$this->templatePath || !is_dir($this->templatePath)) {
            throw new \InvalidArgumentException(sprintf('Invalid template path: %s', $this->templatePath));
        }
    }
}
