<?php

/**
* src/MicroCMS/DependencyInjection/ContainerBuilder.php
*
* Dependency Injection Container Builder
* Builds a Symfony container with the framework dependencies.
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*
*/

namespace MicroCMS\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder as SymContainerBuilder;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use MicroCMS\DependencyInjection\Exception\ConfigFileException;

class ContainerBuilder
{
    /**
     * File locator for locating config files
     * @param Symfony\Component\Config\FileLocatorInterface $configLocator
     */
    protected $configLocator;

    /**
     * The Symfony Container Builder
     * @param Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    protected $container;

    /**
     * Constructor
     *
     * @param Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return null
     */
    public function __construct(SymContainerBuilder $container = null)
    {
        $this->container = ($container) ? $container : new SymContainerBuilder();
    }

    /**
     * prepareContainer
     * Register the MicroCMS core services with the container.
     *
     * @return Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function prepareContainer()
    {
        // Read config files and build services
        $this->readConfig();

        return($this->container);
    }

    /**
     * setConfigLocator
     * Set the file locator for the config files
     *
     * @param Symfony\Component\Config\FileLocatorInterface $config_locator
     * @return self $this
     */
    public function setConfigLocator(FileLocatorInterface $config_locator)
    {
        $this->configLocator = $config_locator;
        return($this);
    }

    /**
     * readConfig
     * Read the configuration files and get service factories.
     *
     * @return void
     */
    protected function readConfig()
    {
        if (!$this->configLocator) {
            throw new \RuntimeException('No FileLocator for config file.');
        }

        // Get kernel environment
        $env = ($this->container->hasParameter('kernel.env')) ?
            $this->container->getParameter('kernel.env') :
            'prod';

        // Build file names
        $coreConfig = 'core.yml';
        $appConfig = 'app_' . $env . '.yml';

        $yamlLoader = new YamlFileLoader($this->container, $this->configLocator);

        // Load the yaml files, core config is required
        try {
            $yamlLoader->load($coreConfig);
        } catch (\InvalidArgumentException $e) {
            throw new ConfigFileException($coreConfig);
        }

        // Application config can be skipped if it doesn't exist.
        try {
            $yamlLoader->load($appConfig);
        } catch (\InvalidArgumentException $e) {}
    }
}
