<?php

/**
* src/MicroCMS/Template/Template.php
*
* MicroCMS Basic Routable Template
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\Template;

class Template extends AbstractTemplate implements TemplateInterface
{
    /**
     * Constructor
     *
     * @param string $renderPath
     * @param string $filename
     * @return null
     */
    public function __construct($renderPath, $filename)
    {
        $this->setRenderName($renderPath);
        $this->setFilename($filename);
    }
}
