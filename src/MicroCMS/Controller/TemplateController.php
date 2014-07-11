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

use MicroCMS\Template\Template;

class TemplateController extends AbstractController
{

    /**
     * indexAction
     * Render a template routed by the template matcher.
     *
     * @param MicroCMS\Template\Template $template;
     * @return Symfony\Component\HttpFoundation\Response;
     */
    public function indexAction(Template $template)
    {
        $templating = $this->container->get('templating');

        $response = new Response(
            $templating->render(
                $template->getRenderName(),
                $template->getRenderData()
            )
        );

        return($response);
    }
}
