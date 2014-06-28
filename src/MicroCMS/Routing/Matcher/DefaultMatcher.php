<?php

/**
* src/MicroCMS/Routing/Matcher/DefaultMatcher.php
*
* A route matcher for the default system routes
* (homepage and 404). This should be the last matcher
* called, and thus should always return a route.
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
use Symfony\Component\Routing\RequestContext;

class DefaultMatcher implements UrlMatcherInterface
{
    /**
     * Templates for default routes
     */
    const NOTFOUND = '404.html';
    const HOME = 'index.html';

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
     * Controller for last resort 404 route
     * @param string errorController
     */
    protected $errorController = 'MicroCMS\\Controller\\ErrorController::notFoundAction';

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
    public function __construct($template_path = null, RequestContext $context)
    {
        $this->templatePath = $template_path;
        $this->context = $context;
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
        $return = null;

        // Default (homepage) - index.html
        if (
            ('/' === $pathinfo) &&
            $this->templatePath &&
            in_array($this->context->getMethod(), array('GET', 'HEAD')) &&
            file_exists($this->templatePath . self::HOME) &&
            is_readable($this->templatePath . self::HOME)
        ) {
            $return = array(
                '_controller' => $this->controller,
                '_route' => 'home',
                '_template' => self::HOME,
            );
        }

        // 404 not found - 404.html
        if (
            !$return &&
            $this->templatePath &&
            file_exists($this->templatePath . self::NOTFOUND) &&
            is_readable($this->templatePath . self::NOTFOUND)
        ) {
            $return = array(
                '_controller' => $this->controller,
                '_route' => '404.html',
                '_template' => self::NOTFOUND,
            );
        }

        // 404 not found - last resort
        if (!$return) {
            $return = array(
                '_controller' => $this->errorController,
                '_route' => '404',
            );
        }

        return($return);
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
}
