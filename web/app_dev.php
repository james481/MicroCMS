<?php

/**
* web/app.php
*
* Main Application Entry (development)
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
*
*/

use MicroCMS\Kernel\Kernel;
use Symfony\Component\HttpFoundation\Request;

require_once(__DIR__ . '/../vendor/autoload.php');

$kernel = new Kernel('dev');
$req = Request::createFromGlobals();
$res = $kernel->handle($req);
$res->send();
$kernel->terminate();
