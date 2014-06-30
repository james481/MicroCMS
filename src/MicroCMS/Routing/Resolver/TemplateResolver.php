<?php

/**
* src/MicroCMS/Routing/Resolver/TemplateResolver.php
*
* Template Request Resolver
* Resolve requests to routable templates
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\Routing\Resolver;

use Symfony\Component\HttpFoundation\Request;

class TemplateResolver
{
    use \MicroCMS\DependencyInjection\LogAwareTrait;

    /**
     * Routable Template Index
     * @param array $templateIndex
     */
    protected $templateIndex;

    /**
     * The routable templates directories, as
     * an array.
     * @param array $templatePath
     */
    protected $templatePath = array();

    /**
     * Constructor
     *
     * @param string|array $template_path
     * @return null
     */
    public function __construct($template_path)
    {
        if (is_array($template_path)) {
            $this->templatePath = $template_path;
        } else {
            $this->templatePath = array($template_path);
        }

        $this->validateTemplatePath();
        $this->buildTemplatesIndex();
    }

    /**
     * resolveIndexTemplate
     * Resolve the template for the homepage (index.html),
     * or false if not found.
     *
     * @return string|bool $template
     */
    public function resolveIndexTemplate()
    {
    }

    /**
     * resolveTemplate
     * Resolve a request into a routable template,
     * or false if none found.
     *
     * @return string|bool $template
     */
    public function resolveTemplate(Request $request)
    {
    }

    /**
     * resolveNotFoundTemplate
     * Resolve the template for the 404 page (_404.html),
     * or false if not found.
     *
     * @return string|bool $template
     */
    public function resolveNotFoundTemplate()
    {
    }

    /**
     * buildTemplatesIndex
     * Build an index array of the routable
     * templates.
     *
     * @return void
     */
    protected function buildTemplatesIndex()
    {
        // We'll iterate the template paths and find
        // any routable templates. Note that if there is
        // more than one directory, the later directory entries
        // may overwrite earlier entires of the same name
        // (i.e. there is no namespacing of paths)

        foreach ($this->tamplatePath as $path) {
        }
    }

    /**
     * validateTemplate
     * Validate a template as readable and routable.
     *
     * @param \SplFileInfo|string $template_file
     * @return bool $is_valid
     */
    protected function validateTemplate($template_file)
    {
        $is_valid = false;

        if (!$template_file instanceof \SplFileInfo) {
            $template_file = new \SplFileInfo($template_file);
        }

        // Is this a routable template?
        if (
            $template_file->isFile() &&
            $template_file->isReadable() &&
            ('.html' === substr($template_file->getFilename(),-5)) &&
            ('_' !== substr($template_file->getFilename(), 0, 1))
        ) {
            $is_valid = true;
        }

        return($is_valid);
    }

    /**
     * validateTemplatePath
     * Validate the template directories.
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function validateTemplatePath()
    {
        array_walk($this->templatePath, function($val, $key) {
            if (!is_dir($val)) {
                throw new \InvalidArgumentException(sprintf('Invalid template path: %s', $val));
            }
        });
    }
}
