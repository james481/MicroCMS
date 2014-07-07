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
use MicroCMS\Template\Resolver;

class TemplateMatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testMatcherMatchesRoutes
     */
    public function testMatcherMatchesRoutes()
    {
        $template_dir = __DIR__ . '/../Fixtures/templates/';
        $resolver = new Resolver($template_dir);
        $context = new RequestContext();

        $matcher = new TemplateMatcher($resolver, $context);

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
        $resolver = new Resolver($template_dir);
        $context = new RequestContext();

        $matcher = new TemplateMatcher($resolver, $context);
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
        $resolver = new Resolver($template_dir);
        $context = new RequestContext();

        $matcher = new TemplateMatcher($resolver, $context);
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
        $resolver = new Resolver($template_dir);
        $context = new RequestContext();

        $matcher = new TemplateMatcher($resolver, $context);
        $matcher->match('/test');
    }
}
