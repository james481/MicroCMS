<?php

/**
* src/MicroCMS/DependencyInjection/LogAwareTrait.php
*
* Trait to allow classes to log to the Monolog logger.
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\DependencyInjection;

use Monolog\Logger;

trait LogAwareTrait
{
    /**
     * The Monolog logger
     * @param Monolog\Logger logger
     */
    protected $logger;

    /**
     * getLogger
     * This is implemented here so it can be
     * overridden by classes if needed.
     *
     * @return Monolog\Logger|null $logger
     */
    public function getLogger()
    {
        $logger = null;

        if ($this->logger) {
            $logger = $this->logger;
        }

        return($logger);
    }

    /**
     * setLogger
     * Set the logger object.
     *
     * @param Monolog\Logger $logger
     * @return void
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * log
     * Log a message to the log handler if set.
     *
     * @param mixed $level
     * @param string $mesg
     * @param array $context
     * @return void
     */
    protected function log($level, $mesg, $context = array())
    {
        if ($logger = $this->getLogger()) {
            $context = array_merge($this->getDefaultContext(), $context);
            $logger->log($level, $mesg, $context);
        }
    }

    /**
     * emergency
     * PSR3 log function - emergency level
     * System is unusable
     *
     * @param string $mesg
     * @param array $context
     * @return void
     */
    protected function emergency($mesg, $context = array())
    {
        $this->log(Logger::EMERGENCY, $mesg, $context);
    }

    /**
     * alert
     * PSR3 log function - alert level
     * Action must be taken immediately.
     *
     * @param string $mesg
     * @param array $context
     * @return void
     */
    protected function alert($mesg, $context = array())
    {
        $this->log(Logger::ALERT, $mesg, $context);
    }

    /**
     * critical
     * PSR3 log function - critical level
     * Critical conditions.
     *
     * @param string $mesg
     * @param array $context
     * @return void
     */
    protected function critical($mesg, $context = array())
    {
        $this->log(Logger::CRITICAL, $mesg, $context);
    }

    /**
     * error
     * PSR3 log function - error level
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $mesg
     * @param array $context
     * @return void
     */
    protected function error($mesg, $context = array())
    {
        $this->log(Logger::ERROR, $mesg, $context);
    }

    /**
     * warning
     * PSR3 log function - warning level
     * Exceptional occurrences that are not errors.
     *
     * @param string $mesg
     * @param array $context
     * @return void
     */
    protected function warning($mesg, $context = array())
    {
        $this->log(Logger::WARNING, $mesg, $context);
    }

    /**
     * notice
     * PSR3 log function - notice level
     * Normal but significant events.
     *
     * @param string $mesg
     * @param array $context
     * @return void
     */
    protected function notice($mesg, $context = array())
    {
        $this->log(Logger::NOTICE, $mesg, $context);
    }

    /**
     * info
     * PSR3 log function - info level
     * Interesting events.
     *
     * @param string $mesg
     * @param array $context
     * @return void
     */
    protected function info($mesg, $context = array())
    {
        $this->log(Logger::INFO, $mesg, $context);
    }

    /**
     * debug
     * PSR3 log function - debug level
     * Detailed debug information.
     *
     * @param string $mesg
     * @param array $context
     * @return void
     */
    protected function debug($mesg, $context = array())
    {
        $this->log(Logger::DEBUG, $mesg, $context);
    }

    /**
     * getDefaultContext
     * Get the default context for log messages.
     *
     * @return array $context
     */
    protected function getDefaultContext()
    {
        $context = array(
            'class' => get_class($this),
        );

        return($context);
    }
}
