<?php

/**
* src/MicroCMS/Routing/Router.php
*
* Stacked router implmentation that calls the
* various system matchers in the reverse order they're
* registered. Currently only provides matchers, not
* generators.
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\Routing;

use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class Router implements UrlMatcherInterface
{
    use \MicroCMS\DependencyInjection\LogAwareTrait;

    /**
     * RequestContext object
     * @param Symfony\Component\Routing\RequestContext $context
     */
    protected $context;

    /**
     * Stack of url matchers
     * @param \SplStack $matchers
     */
    protected $matchers;

    /**
     * Constructor
     *
     * @return null
     */
    public function __construct()
    {
        $this->matchers = new \SplStack();
    }

    /**
     * addMatcher
     * Add a new matcher to the stack.
     *
     * @return self $this
     */
    public function addMatcher(UrlMatcherInterface $matcher)
    {
        $this->debug(sprintf('addMatcher: Adding matcher %s', get_class($matcher)));
        $this->matchers->push($matcher);
        return($this);
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
     * getMatchers
     * Get the array of registered matchers.
     *
     * @return array $matchers
     */
    public function getMatchers()
    {
        return($this->matchers);
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
        $routes = array();
        $this->debug(sprintf('match: Matching %s', $pathinfo));

        foreach ($this->matchers as $matcher) {
            try {
                $routes = $matcher->match($pathinfo);
                break;
            } catch (ResourceNotFoundException $e) {}
        }

        if (!isset($routes['_route']) || !isset($routes['_controller'])) {
            $mesg = 'match: No Routes Found.';
            $this->alert($mesg);
            throw new RoutingException($mesg);
        }

        $this->debug(sprintf('match: Route matched %s', $routes['_route']));

        return($routes);
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
        foreach ($this->matchers as $matcher) {
            $matcher->setContext($context);
        }

        $this->context = $context;
    }
}
