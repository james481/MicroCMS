<?php

/**
* src/MicroCMS/Controller/TemplateController.php
*
* MicroCMS Routable Template Rendering Controller
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
use MicroCMS\Routing\Matcher\DefaultMatcher;

class TemplateController extends AbstractController
{

    /**
     * indexAction
     * Render a template routed by the template matcher.
     *
     * @param Symfony\Component\HttpFoundation\Request;
     * @return Symfony\Component\HttpFoundation\Response;
     */
    public function indexAction(Request $request)
    {
        return($this->getResponse());
    }
}
