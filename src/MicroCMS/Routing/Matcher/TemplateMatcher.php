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
     * Controller for template routes
     * @param string $controller
     */
    protected $controller = 'MicroCMS\\Controller\\TemplateController::indexAction';

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
        $this->routes = new RouteCollection();

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
     * @param string $pathinfo
     * @return array $return
     *
     * @throws ResourceNotFoundException If the resource could not be found
     * @throws MethodNotAllowedException If the resource was found but the request method is not allowed
     */
    public function match($pathinfo)
    {
        // Template router only accepts GET or HEAD requests
        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
            throw new MethodNotAllowedException(array('GET', 'HEAD'));
        }

        $matched_route = null;
        foreach ($this->routes as $name => $route) {
            // TODO Hostname / other route requirements?
            if ($pathinfo === $route->getPath()) {
                $matched_route = $route;
                break;
            }
        }

        if ($matched_route) {
            $return = array_merge(
                array(
                    '_controller' => $this->controller,
                    '_route' => $route->getPath(),
                ),
                $matched_route->getDefaults()
            );

            return($return);
        } else {
            throw new ResourceNotFoundException();
        }
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

        $templates = new \FilesystemIterator($this->templatePath);

        foreach ($templates as $template) {

            // Is this a routable template?
            if (
                $template->isFile() &&
                $template->isReadable() &&
                ('.html' === substr($template->getFilename(),-5)) &&
                ('_' !== substr($template->getFilename(), 0, 1))
            ) {

                // Construct routes for both 'foo.html' and 'foo'
                $template_fullname = urlencode(strtolower($template->getFilename()));
                $template_shortname = substr($template_fullname, 0, -5);
                $defaults = array('_template' => $template->getFilename());

                $this->routes->add($template_fullname, new Route($template_fullname, $defaults));
                $this->routes->add($template_shortname, new Route($template_shortname, $defaults));
            }
        }
    }
}
