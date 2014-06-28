<?php

/**
* tests/MicroCMS/Routing/Matcher/TemplateMatcherTest.php
*
* Test the MicroCMS Routing TemplateMatcher
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\Routing\Matcher;

use Symfony\Component\Routing\RequestContext;

class TemplateMatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testMatcherBuildsRoutes
     */
    public function testMatcherBuildsRoutes()
    {
        $template_dir = __DIR__ . '/../Fixtures/templates/';
        $context = new RequestContext();

        $matcher = new TemplateMatcher($template_dir, $context);

        $ref = new \ReflectionClass($matcher);
        $routes_ref = $ref->getProperty('routes');
        $routes_ref->setAccessible(true);
        $routes = $routes_ref->getValue($matcher)->all();

        // Assert we have routes for the test template
        $this->assertCount(2, $routes);
        $this->assertArrayHasKey('test', $routes);
        $this->assertInstanceOf('Symfony\Component\Routing\Route', $routes['test']);

        $this->assertArrayHasKey('test.html', $routes);
        $this->assertInstanceOf('Symfony\Component\Routing\Route', $routes['test.html']);

        // Assert that we don't have routes for unroutable '_*.html' templates
        $this->assertArrayNotHasKey('_noroute', $routes);
        $this->assertArrayNotHasKey('_noroute.html', $routes);
    }

    /**
     * testMatcherMatchesRoutes
     */
    public function testMatcherMatchesRoutes()
    {
        $template_dir = __DIR__ . '/../Fixtures/templates/';
        $context = new RequestContext();

        $matcher = new TemplateMatcher($template_dir, $context);

        // match will throw if it doesn't find something
        $match = $matcher->match('/test');
        $this->assertEquals('/test', $match['_route']);
        $this->assertEquals('test.html', $match['_template']);

        $match = $matcher->match('/test.html');
        $this->assertEquals('/test.html', $match['_route']);
        $this->assertEquals('test.html', $match['_template']);
    }

    /**
     * testMatcherThrowsNotfoundException
     *
     * @expectedException Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function testMatcherThrowsNotfoundException()
    {
        $template_dir = __DIR__ . '/../Fixtures/templates/';
        $context = new RequestContext();

        $matcher = new TemplateMatcher($template_dir, $context);
        $matcher->match('/invalid');
    }

    /**
     * testMatcherThrowsUnroutableException
     *
     * @expectedException Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function testMatcherThrowsUnroutableException()
    {
        $template_dir = __DIR__ . '/../Fixtures/templates/';
        $context = new RequestContext();

        $matcher = new TemplateMatcher($template_dir, $context);
        $matcher->match('/_noroute');
    }

    /**
     * testMatcherThrowsMethodException
     *
     * @return void
     * @expectedException Symfony\Component\Routing\Exception\MethodNotAllowedException

     */
    public function testMatcherThrowsMethodException()
    {
        $template_dir = __DIR__ . '/../Fixtures/templates/';
        $context = new RequestContext('/test', 'POST');

        $matcher = new TemplateMatcher($template_dir, $context);
        $matcher->match('/test');
    }
}
