<?php

/**
* src/MicroCMS/Kernel/LogAwareTrait.php
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

namespace MicroCMS\Kernel;

use Monolog\Logger;

trait LogAwareTrait
{
    /**
     * The Monolog logger
     * @param Monolog\Logger logger
     */
    protected $logger;

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
}
