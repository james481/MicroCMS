<?php

/**
* src/MicroCMS/DependencyInjection/ContainerBuilder.php
*
* Dependency Injection Container Builder
* Builds a Symfony container with the framework dependencies.
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
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
     * @param Symfony\Component\Config\FileLocatorInterface $config_locator
     */
    protected $config_locator;

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
        $this->config_locator = $config_locator;
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
        if (!$this->config_locator) {
            throw new \RuntimeException('No FileLocator for config file.');
        }

        // Get kernel environment
        $env = ($this->container->hasParameter('kernel.env')) ?
            $this->container->getParameter('kernel.env') :
            null;

        // Build file names
        $coreConfig = 'core_' . $env . '.yml';
        $appConfig = 'app_' . $env . '.yml';

        $yamlLoader = new YamlFileLoader($this->container, $this->config_locator);

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
