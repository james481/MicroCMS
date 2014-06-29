<?php

/**
* src/MicroCMS/Controller/AbstractController.php
*
* MicroCMS Abstract Controller
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController implements ContainerAwareInterface
{
    use
        \Symfony\Component\DependencyInjection\ContainerAwareTrait,
        \MicroCMS\DependencyInjection\LogAwareTrait;

    /**
     * getLogger
     * Get the logger from the container if available,
     * and provide it to the LogAwareTrait log method.
     *
     * @return Monolog\Logger|null $logger
     */
    public function getLogger()
    {
        $logger = null;

        if ($this->container && $this->container->has('logger')) {
            $logger = $this->container->get('logger');
        }

        return($logger);
    }

    /**
     * getRequest
     * Get the request object from the container
     *
     * @return Symfony\Component\HttpFoundation\Request $request;
     */
    public function getRequest()
    {
        $request = null;

        if ($this->container && $this->container->has('request')) {
            $request = $this->container->get('request');
        }

        return($request);
    }

    /**
     * getResponse
     *
     * @return Symfony\Component\HttpFoundation\Response $response;
     */
    public function getResponse()
    {
        return(new Response());
    }
}
