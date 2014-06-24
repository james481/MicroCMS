<?php

/**
* src/MicroCMS/Kernel/Resolver.php
*
* Application Controller Resolver
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\Kernel;

use Symfony\Component\HttpFoundation\Request;

class Resolver
{
    /**
     * resolveController
     * Resolve a route string ('controller::method') into a
     * callable, or false if it doesn't exist.
     *
     * @param string $controller
     * @return mixed|bool $callable
     */
    public function resolveController($controller)
    {
        $callable = false;

        if (false !== strpos($controller, '::')) {
            list($controller_class, $method) = explode($controller, '::', 2);

            if (class_exists($controller_class)) {

                // Create controller class, and make sure method is callable
                $callable = array(new $controller_class(), $method);
                $callable = (is_callable($callable)) ? $callable : false;
            }
        }

        return($callable);
    }

    /**
     * resolveArguments
     * Resolve controller method arguments from the request object.
     *
     * @return array $arguments
     */
    public function resolveArguments(Array $callable, Request $request)
    {
        $arguments = array();

        // Examine the controller method with reflection and
        // build arguments
        $ref = new \ReflectionMethod($callable[0], $callable[1]);
        $req_attr = $request->attributes->all();

        foreach ($ref->getParameters() as $parameter) {
            if (array_key_exists($parameter->name, $req_attr)) {
                $arguments[] = $req_attr[$parameter->name];
            } elseif ($parameter->getClass() && $parameter->getClass()->isInstance($request)) {
                $arguments[] = $request;
            } elseif ($parameter->isDefaultValueAvailable()) {
                $arguments[] = $parameter->getDefaultValue();
            } else {
                throw new \RuntimeException(sprintf('Controller %s::%s requires a value for parameter %s.', $callable[0], $callable[1], $parameter->name));
            }
        }

        return($arguments);
    }
}
