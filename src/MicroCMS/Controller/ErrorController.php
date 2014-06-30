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
    const NOTFOUND_CONTENT = '<h1>404</h1><br />This page could not be found.';

    /**
     * notFoundAction
     * This action is called if the router generated a 404
     * and couldn't find a template to render for it.
     *
     * @return Symfony\Component\HttpFoundation\Response;
     */
    public function notFoundAction()
    {
        $res = $this->getResponse();
        $res->setStatusCode(Response::HTTP_NOT_FOUND);
        $res->setContent(self::NOTFOUND_CONTENT);

        return($res);
    }
}
