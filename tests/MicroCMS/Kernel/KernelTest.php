<?php

/**
* tests/MicroCMS/Kernel/KernelTest.php
*
* Test the MicroCMS Main Application Kernel.
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\Kernel;

class KernelTest extends AbstractKernelTest
{
    /**
     * getTestKernelInstance
     *
     * @return MicroCMS\Kernel\AbstractKernel $kernel
     */
    protected function getTestKernelInstance()
    {
        return(new Kernel('test'));
    }
}
