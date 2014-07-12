<?php

/**
* tests/MicroCMS/Kernel/ResolverTest.php
*
* Test the MicroCMS Controller Resolver
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\Kernel;

use Symfony\Component\HttpFoundation\Request;

class ResolverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * testResolveController
     */
    public function testResolveController()
    {
        $resolver = new Resolver();

        // Test that core controllers resolve
        $controller = 'MicroCMS\\Controller\\TemplateController::indexAction';
        $callable = $resolver->resolveController($controller);
        $this->assertInternalType('callable', $callable);

        // Test bogus controller returns false
        $controller = 'MicroCMS\\Controller\\InvalidController::indexAction';
        $callable = $resolver->resolveController($controller);
        $this->assertFalse($callable);

        // Test controller with no method returns false
        $controller = 'MicroCMS\\Controller\\TemplateController';
        $callable = $resolver->resolveController($controller);
        $this->assertFalse($callable);
    }

    /**
     * testResolveEmptyArguments
     */
    public function testResolveEmptyArguments()
    {
        $resolver = new Resolver();
        $class = new ResolverTestController();
        $req = new Request();
        $callable = array($class, 'noArgs');
        $route_data = array();
        $args = $resolver->resolveArguments($callable, $req, $route_data);

        $this->assertEmpty($args);
    }

    /**
     * testResolveRequestArguments
     */
    public function testResolveRequestArguments()
    {
        $resolver = new Resolver();
        $class = new ResolverTestController();
        $req = Request::create('/test?reqdata=1');
        $callable = array($class, 'reqParam');
        $route_data = array();
        $args = $resolver->resolveArguments($callable, $req, $route_data);

        $this->assertCount(1, $args);
        $this->assertEquals(1, $args[0]);
    }

    /**
     * testResolveRequestClassArguments
     */
    public function testResolveRequestClassArguments()
    {
        $resolver = new Resolver();
        $class = new ResolverTestController();
        $req = new Request();
        $callable = array($class, 'reqClass');
        $route_data = array();
        $args = $resolver->resolveArguments($callable, $req, $route_data);

        $this->assertCount(1, $args);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Request', $args[0]);
    }

    /**
     * testResolveRouteArguments
     */
    public function testResolveRouteArguments()
    {
        $resolver = new Resolver();
        $class = new ResolverTestController();
        $req = new Request();
        $callable = array($class, 'routeParam');
        $route_data = array('routedata' => 1);
        $args = $resolver->resolveArguments($callable, $req, $route_data);

        $this->assertCount(1, $args);
        $this->assertEquals(1, $args[0]);
    }

    /**
     * testResolveDefaultArguments
     */
    public function testResolveDefaultArguments()
    {
        $resolver = new Resolver();
        $class = new ResolverTestController();
        $req = new Request();
        $callable = array($class, 'defVal');
        $route_data = array();
        $args = $resolver->resolveArguments($callable, $req, $route_data);

        $this->assertCount(1, $args);
        $this->assertEquals(1, $args[0]);
    }

    /**
     * testResolveMixedArguments
     */
    public function testResolveMixedArguments()
    {
        $resolver = new Resolver();
        $class = new ResolverTestController();
        $req = Request::create('/test?reqdata=1');
        $callable = array($class, 'mixed');
        $route_data = array('routedata' => 1);
        $args = $resolver->resolveArguments($callable, $req, $route_data);

        $this->assertCount(3, $args);
        $this->assertEquals(1, $args[0]);
        $this->assertEquals(1, $args[1]);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Request', $args[2]);
    }

    /**
     * testThrowsUnresolvableError
     *
     * @expectedException \RuntimeException
     */
    public function testThrowsUnresolvableError()
    {
        $resolver = new Resolver();
        $class = new ResolverTestController();
        $req = new Request();
        $callable = array($class, 'invalidArgs');
        $route_data = array();
        $args = $resolver->resolveArguments($callable, $req, $route_data);
    }

}

/**
 * Mock controller class for testing method argument resolving.
 */
class ResolverTestController {
    public function noArgs() {}
    public function reqParam($reqdata) {}
    public function reqClass(Request $req) {}
    public function routeParam($routedata) {}
    public function defVal($defval = 1) {}
    public function invalidArgs($invalid) {}
    public function mixed($reqdata, $routedata, Request $req) {}
}
