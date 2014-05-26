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
}
