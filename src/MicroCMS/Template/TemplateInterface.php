<?php

/**
* src/MicroCMS/Template/TemplateInterface.php
*
* Interface for Routable Template Objects
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\Template;

interface TemplateInterface
{
    /**
     * getFilename
     * Get the full disk path to the template file.
     *
     * @return string $filename
     */
    public function getFilename();

    /**
     * getRenderName
     * Get the renderable path for the template
     * (passed to template engine for render).
     *
     * @return string $template_name
     */
    public function getRenderName();

    /**
     * getRenderData
     * Get any data needed to render the template
     * (passed to the template engine for render).
     *
     * @return array $render_data
     */
    public function getRenderData();
}
