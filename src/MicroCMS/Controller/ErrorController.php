<?php

/**
* src/MicroCMS/Controller/ErrorController.php
*
* MicroCMS Error (Exception, 404) Controller
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\Controller;

use Symfony\Component\HttpFoundation\Response;

class ErrorController extends AbstractController
{
    const ERROR_CONTENT = '<h1>500</h1><br />Internal Server Error.';
    const NOTFOUND_CONTENT = '<h1>404</h1><br />This page could not be found.';

    /**
     * displayTraceAction
     * This action is called if an exception is generated on a
     * non-production system, and will display a pretty stack trace.
     * @TODO Make stack trace pretty
     *
     * @param \Exception $exception
     * @return Symfony\Component\HttpFoundation\Response $res;
     */
    public function displayTraceAction(\Exception $exception)
    {
        $res = $this->getResponse();
        $res->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        $res->setContent(
            sprintf('Exception: %s<br>%s',
                $exception->getMessage(),
                nl2br($exception->getTraceAsString())
            )
        );

        return($res);

    }

    /**
     * errorAction
     * This action is called if an exception is generated on a
     * production system and no error template was found.
     *
     * @return Symfony\Component\HttpFoundation\Response $res;
     */
    public function errorAction()
    {
        $res = $this->getResponse();
        $res->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        $res->setContent(self::ERROR_CONTENT);

        return($res);
    }

    /**
     * notFoundAction
     * This action is called if the router generated a 404
     * and couldn't find a template to render for it.
     *
     * @return Symfony\Component\HttpFoundation\Response $res;
     */
    public function notFoundAction()
    {
        $res = $this->getResponse();
        $res->setStatusCode(Response::HTTP_NOT_FOUND);
        $res->setContent(self::NOTFOUND_CONTENT);

        return($res);
    }
}
