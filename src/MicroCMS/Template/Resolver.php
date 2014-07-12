<?php

/**
* src/MicroCMS/Template/Resolver.php
*
* Template Resolver
* Resolve to routable templates
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\Template;

class Resolver
{
    use \MicroCMS\DependencyInjection\LogAwareTrait;

    /**
     * The template for the root (/) page
     */
    const INDEX_TEMPLATE = '/index.html';

    /**
     * The template for 404 Pages
     */
    const NOTFOUND_TEMPLATE = '/_404.html';

    /**
     * Routable Template Index
     * @param array $templateIndex
     */
    protected $templateIndex = array();

    /**
     * The routable templates directories, as
     * an array.
     * @param array $templatePath
     */
    protected $templatePath = array();

    /**
     * Has the template index been built
     * @param bool $templateIndexBuilt
     */
    private $templateIndexBuilt = false;

    /**
     * Constructor
     *
     * @param string|array $template_path
     * @return null
     */
    public function __construct($template_path = array())
    {
        $this->templatePath = (array) $template_path;
        $this->validateTemplatePath();
    }

    /**
     * hasRoutableTemplates
     * Determine if routable templates other than
     * home and 404 exist.
     *
     * @return bool $routable
     */
    public function hasRoutableTemplates()
    {
        $this->buildTemplateIndex();

        $routable = false;

        foreach ($this->templateIndex as $name => $template) {
            if (($name !== self::INDEX_TEMPLATE) && ($name !== self::NOTFOUND_TEMPLATE)) {
                $routable = true;
                break;
            }
        }

        return($routable);
    }

    /**
     * resolveIndexTemplate
     * Resolve the template for the homepage (index.html),
     * or false if not found.
     *
     * @return MicroCMS\Template\Template|bool $template
     */
    public function resolveIndexTemplate()
    {
        $this->buildTemplateIndex();

        $template = (array_key_exists(self::INDEX_TEMPLATE, $this->templateIndex)) ?
            $this->templateIndex[self::INDEX_TEMPLATE] :
            false;

        return($template);
    }

    /**
     * resolveTemplate
     * Resolve a request type string to a template,
     * or false if none found.
     *
     * @param string $request_string
     * @return MicroCMS\Template\Template|bool $template
     */
    public function resolveTemplate($request_string)
    {
        $this->debug(sprintf('resolveTemplate: Resolving %s.', $request_string));

        $template = false;

        $this->buildTemplateIndex();

        // First break off any GET vars appended to the URL, splitting
        // either on '?' or '.html'.
        if (false !== strpos($request_string, '?')) {
            $request_string = substr($request_string, 0, strpos($request_string, '?'));
        } elseif (false !== strpos($request_string, '.html')) {
            $request_string = substr($request_string, 0, strpos($request_string, '.html') + 5);
        }

        // Append .html if not already there
        if ('.html' !== substr($request_string, -5)) {
            $request_string .= '.html';
        }

        $this->debug(sprintf('resolveTemplate: Matching relative template path %s.', $request_string));

        // Search the index for a matching template
        if (array_key_exists($request_string, $this->templateIndex)) {
            $template = $this->templateIndex[$request_string];

            $this->debug(sprintf(
                'resolveTemplate: Resolved Template %s (%s).',
                $template->getRenderName(),
                $template->getFilename()
            ));
        }

        return($template);
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
        $this->buildTemplateIndex();

        $template = (array_key_exists(self::NOTFOUND_TEMPLATE, $this->templateIndex)) ?
            $this->templateIndex[self::NOTFOUND_TEMPLATE] :
            false;

        return($template);
    }

    /**
     * buildTemplateIndex
     * Build an index array of the routable
     * templates.
     *
     * @return void
     */
    protected function buildTemplateIndex()
    {
        // We'll iterate the template paths and find
        // any routable templates. Note that if there is
        // more than one directory, the earlier directories
        // are checked first, then on down the stack to
        // mimic the behavior of Twig_Loader_Filesystem.

        if (false === $this->templateIndexBuilt) {
            foreach ($this->templatePath as $path) {
                $dir = new \RecursiveDirectoryIterator($path);
                $it = new \RecursiveIteratorIterator($dir);

                foreach ($it as $template_file) {
                    if ($this->validateTemplate($template_file)) {

                        // Strip the path from the template file relative to
                        // the root template directory to get the template "route"
                        $template_full_path = $template_file->getPathname();
                        $template_rel_path = str_replace($path, '', $template_full_path);
                        $template_route = '/' . $template_rel_path;

                        if (!array_key_exists($template_route, $this->templateIndex)) {
                            $this->debug(sprintf('buildTemplateIndex: Routable Template %s Found at %s.', $template_rel_path, $template_full_path));

                            $template = new Template($template_rel_path, $template_full_path);
                            $this->templateIndex[$template_route] = $template;
                        }
                    }
                }
            }

            $this->templateIndexBuilt = true;
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
            ('html' === $template_file->getExtension())
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
