<?php

/**
* src/MicroCMS/Kernel/AbstractKernel.php
*
* Application Kernel Abstract
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
*
*/

namespace MicroCMS\Kernel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AbstractKernel
{
    /**
     * The application environment
     * @param string env
     */
    public $env;

    /**
     * The request object
     * @param Symfony\Component\HttpFoundation\Request $request
     */
    public $request;

    /**
     * Constructor
     *
     * @param string $env Application Environment
     * @return null
     */
    public function __construct($env = 'prod')
    {
        $this->env = $env;
        $this->bootstrap();
    }

    /**
     * bootstrap
     * Bootstrap the application and pass the request
     * to handle.
     *
     * @return void
     */
    protected function bootstrap()
    {
        $this->request = Request::createFromGlobals();
        $response = $this->handle($this->request);
        $response->send();
        $this->terminate();
    }

    /**
     * handle
     * The main application handler, extended by children
     * kernels.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    protected function handle(Request $request)
    {
        $response = new Response();
        return($response);
    }

    /**
     * terminate
     * Clean up after the response has been sent.
     *
     * @return void
     */
    protected function terminate()
    {
    }
}
