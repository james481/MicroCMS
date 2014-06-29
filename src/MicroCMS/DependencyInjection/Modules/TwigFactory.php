<?php

/**
* src/MicroCMS/DependencyInjection/Modules/TwigFactory.php
*
* Build the container service for the twig rendering engine.
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\DependencyInjection\Modules;

use Symfony\Component\Filesystem\FileSystem;

class TwigFactory implements ModuleFactoryInterface
{
    /**
     * The Twig cache directory (relative to kernel.cache_dir)
     */
    const CACHE_DIR = '/twig/';

    /**
     * The system cache directory
     * @param string cacheDir
     */
    protected $cacheDir;

    /**
     * The kernel environment
     * @param string env
     */
    protected $env;

    /**
     * Templates directory
     * @param string $templateDir
     */
    protected $templateDir;

    /**
     * Constructor
     *
     * @param string $template_dir
     * @param string $cache_dir = null
     * @param string $kernel_env = 'prod'
     * @return null
     */
    public function __construct($template_dir, $cache_dir = null, $kernel_env = 'prod')
    {
        $this->templateDir = $template_dir;
        $this->cacheDir = $cache_dir;
        $this->env = $kernel_env;
    }

    /**
     * getServiceClass
     *
     * @return void
     */
    public function getServiceClass()
    {
        $this->checkTemplateDirectory();

        // If we're not on dev, setup twig cache directory
        $cache_dir = ('dev' !== $this->env) ? $this->getCacheDirectory() : false;

        // Twig FileLoader for templates
        $loader = new \Twig_Loader_Filesystem($this->templateDir);

        $options = array(
            'cache' => $cache_dir,
        );

        // Turn on debug if we're on dev
        $options['debug'] = ('dev' === $this->env);

        $twig = new \Twig_Environment($loader, $options);

        return($twig);
    }

    /**
     * checkTemplateDirectory
     *
     * @return void
     */
    protected function checkTemplateDirectory()
    {
        if (!$this->templateDir || !is_dir($this->templateDir)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid template directory: %s', $this->templateDir)
            );
        }
    }

    /**
     * getCacheDirectory
     * Get the absolute path to the twig cache directory,
     * if available (or else false).
     *
     * @return string|bool $cache_dir
     */
    protected function getCacheDirectory()
    {
        $cache_dir = false;

        if ($this->cacheDir && is_dir($this->cacheDir)) {
            $twig_cache = $this->cacheDir . self::CACHE_DIR;
            if (is_dir($twig_cache) && is_writable($twig_cache)) {
                $cache_dir = $twig_cache;
            } else {

                // Attempt to create twig cache dir
                $fs = new FileSystem();

                try {
                    $fs->mkdir($twig_cache);
                } catch (\Exception $e) {}

                $cache_dir = $twig_cache;
            }
        }

        return($cache_dir);
    }
}
