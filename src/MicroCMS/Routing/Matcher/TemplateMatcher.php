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
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use MicroCMS\Template\Resolver;
use MicroCMS\Template\Template;

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
     * Template Resolver
     * @param MicroCMS\Template\Resolver $resolver
     */
    protected $resolver;

    /**
     * Prefix for template files that will not be
     * routed automatically.
     * @param string $unroutablePrefix = '_'
     */
    protected $unroutablePrefix = '_';

    /**
     * Constructor
     *
     * @param MicroCMS\Template\Resolver $resolver
     * @param Symfony\Component\Routing\RequestContext $context
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

        $return = null;

        // Resolve the template and make sure it's routable
        $template = $this->resolver->resolveTemplate(rawurldecode($pathinfo));

        if ((false !== $template) && $this->isRoutableTemplate($template)) {
            $return = array(
                '_controller' => $this->controller,
                '_route' => $template->getRenderName(),
            );
        }

        if ($return) {
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
     * isRoutableTemplate
     * Determine if the template from the resolver should be
     * routed.
     *
     * @param MicroCMS\Template\Template $template
     * @return bool $is_routable
     */
    protected function isRoutableTemplate(Template $template)
    {
        $template_file = new \SplFileInfo($template->getFilename());

        $is_routable =
            ($this->unroutablePrefix !== substr($template_file->getBasename(), 0, strlen($this->unroutablePrefix)));

        return($is_routable);
    }
}
