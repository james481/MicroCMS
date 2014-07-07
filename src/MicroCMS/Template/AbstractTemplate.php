<?php

/**
* src/MicroCMS/Template/AbstractTemplate.php
*
* Abstract Routable Template
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\Template;

abstract class AbstractTemplate implements TemplateInterface
{
    /**
     * The full disk path to the template file
     * @param string $filename
     */
    protected $filename;

    /**
     * The renderable path to the template
     * (relative to the template root dir)
     * @param string $renderName
     */
    protected $renderName;

    /**
     * Array of data to be passed to the template renderer
     * @param array $renderData
     */
    protected $renderData = array();

    /**
     * getFilename
     * Get the full disk path to the template file.
     *
     * @return string $filename
     */
    public function getFilename()
    {
        return($this->filename);
    }

    /**
     * getRenderName
     * Get the renderable path for the template
     * (passed to template engine for render).
     *
     * @return string $template_name
     */
    public function getRenderName()
    {
        return($this->renderName);
    }

    /**
     * getRenderData
     * Get any data needed to render the template
     * (passed to the template engine for render).
     *
     * @return array $render_data
     */
    public function getRenderData()
    {
        return($this->renderData);
    }

    /**
     * setFilename
     * Set the full disk path to the template file.
     *
     * @param string $filename
     * @return self $this
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return($this);
    }

    /**
     * setRenderName
     * Set the renderable path for the template
     * (passed to template engine for render).
     *
     * @param string $template_name
     * @return self $this
     */
    public function setRenderName($template_name)
    {
        $this->renderName = $template_name;
        return($this);
    }

    /**
     * setRenderData
     * Set any data needed to render the template
     * (passed to the template engine for render).
     *
     * @param array $render_data
     * @return self $this
     */
    public function setRenderData(Array $render_data)
    {
        $this->renderData = $render_data;
        return($this);
    }
}
