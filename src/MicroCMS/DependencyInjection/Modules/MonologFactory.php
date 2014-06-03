<?php

/**
* src/MicroCMS/DependencyInjection/Modules/MonologFactory.php
*
* Build the container service for monolog logger.
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
*
*/

namespace MicroCMS\DependencyInjection\Modules;

class MonologFactory implements ModuleFactoryInterface
{
    /**
     * The Log Directory
     * @param string logDir
     */
    protected $logDir;

    /**
     * The kernel environment
     * @param string env
     */
    protected $env;

    /**
     * Constructor
     *
     * @param string $logDir The log directory
     * @param string $env The kernel environment
     * @return null
     */
    public function __construct($logDir, $env = 'prod')
    {
        $this->logDir = $logDir;
        $this->env = $env;
    }

    /**
     * getServiceClass
     * Build the Monolog logger for use in the container.
     *
     * @return Monolog\Logger $logger
     */
    public function getServiceClass()
    {
        return($this);
    }
}
