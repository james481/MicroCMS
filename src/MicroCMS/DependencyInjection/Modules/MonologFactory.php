<?php

/**
* src/MicroCMS/DependencyInjection/Modules/MonologFactory.php
*
* Build the container service for monolog logger.
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*
*/

namespace MicroCMS\DependencyInjection\Modules;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class MonologFactory implements ModuleFactoryInterface
{
    /**
     * The Log Directory
     * @param string logDir
     */
    protected $logDir;

    /**
     * The name of the log file to be written
     * @param string logFile
     */
    protected $logFile;

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
        $this->logFile = $this->logDir . '/app_' . $this->env . '.log';
    }

    /**
     * getServiceClass
     * Build the Monolog logger for use in the container.
     *
     * @return Monolog\Logger $logger
     */
    public function getServiceClass()
    {
        $this->checkFilePermissions();
        $logger_name = 'MicroCMS_' . $this->env;
        $logger = new Logger($logger_name);
        $logger->pushHandler(
            new StreamHandler($this->logFile, $this->getHandlerLevel())
        );

        return($logger);
    }

    /**
     * checkFilePermissions
     * Check the log directory / file and make sure
     * they're writable. If the log file doesn't exist
     * it's created here.
     *
     * @return void
     */
    protected function checkFilePermissions()
    {
        if (!is_dir($this->logDir)) {
            throw new \InvalidArgumentException(
                sprintf('Cannot locate log directory: %s', $this->logDir)
            );
        }

        if ((!file_exists($this->logFile)) && (!touch($this->logFile))) {
            throw new \InvalidArgumentException(
                sprintf('Cannot create log file: %s', $this->logFile)
            );
        } elseif (!is_writable($this->logFile)) {
            throw new \InvalidArgumentException(
                sprintf('Cannot write to log file: %s', $this->logFile)
            );
        }
    }

    /**
     * getHandlerLevel
     * Get the minimum log level for the handler, based
     * on the kernel environment.
     *
     * @return int $logLevel
     */
    protected function getHandlerLevel()
    {
        $logLevel = ('prod' === $this->env) ? Logger::ERROR : Logger::DEBUG;
        return($logLevel);
    }
}
