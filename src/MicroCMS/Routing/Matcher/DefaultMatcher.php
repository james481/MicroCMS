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
use MicroCMS\Template\Resolver;

class DefaultMatcher implements UrlMatcherInterface
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
     * Controller for last resort 404 route
     * @param string errorController
     */
    protected $errorController = 'MicroCMS\\Controller\\ErrorController::notFoundAction';

    /**
     * Template Resolver
     * @param MicroCMS\Template\Resolver $resolver
     */
    protected $resolver;

    /**
     * Constructor
     *
     * @param MicroCMS\Template\Resolver $resolver
     * @param mixed RequestContext $context
     * @return null
     */
    public function __construct(Resolver $resolver, RequestContext $context)
    {
        $this->resolver = $resolver;
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
     */
    public function match($pathinfo)
    {
        $return = null;

        // Default (homepage) - index.html
        if (
            ('/' === $pathinfo) &&
            ($index_template = $this->resolver->resolveIndexTemplate())
        ) {
            $return = array(
                '_controller' => $this->controller,
                '_route' => $index_template->getRenderName(),
            );
        }

        // 404 not found - 404.html

        if (!$return && ($nf_template = $this->resolver->resolveNotFoundTemplate())) {
            $return = array(
                '_controller' => $this->controller,
                '_route' => $nf_template->getRenderName(),
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
