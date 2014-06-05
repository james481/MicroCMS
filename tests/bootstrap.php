<?php

/**
* tests/bootstrap.php
*
* MicroCMS test suite bootstrap
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

$loader = require __DIR__ . "/../vendor/autoload.php";
$loader->addPsr4('MicroCMS\\', __DIR__.'/MicroCMS');
