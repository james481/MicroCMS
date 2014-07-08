<?php

/**
* tests/MicroCMS/Routing/Matcher/DefaultMatcherTest.php
*
* Test the MicroCMS Routing DefaultMatcher
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

class DefaultMatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testMatcherHomepageRoute
     */
    public function testMatcherHomepageRoute()
    {
        $template_dir = __DIR__ . '/../Fixtures/templates/';
        $resolver = new Resolver($template_dir);
        $context = new RequestContext();

        $matcher = new DefaultMatcher($resolver, $context);
        $match = $matcher->match('/');

        $this->assertArrayHasKey('_controller', $match);
        $this->assertArrayHasKey('_route', $match);
        $this->assertEquals(Resolver::INDEX_TEMPLATE, '/' . $match['_route']);
    }

    /**
     * testMatcherHomepageNoTemplate
     */
    public function testMatcherHomepageNoTemplate()
    {
        // If no homepage template is found, we should get 404
        $template_dir = __DIR__ . '/../Fixtures/templates_empty/';
        $resolver = new Resolver($template_dir);
        $context = new RequestContext();

        $matcher = new DefaultMatcher($resolver, $context);
        $match = $matcher->match('/');

        $this->assertArrayHasKey('_controller', $match);
        $this->assertArrayHasKey('_route', $match);
        $this->assertEquals('404', $match['_route']);
    }

    /**
     * testMatcherNotFoundTemplate
     */
    public function testMatcherNotFoundTemplate()
    {
        // If we have a 404 template, we should get
        // a route to the template controller on 404
        $template_dir = __DIR__ . '/../Fixtures/templates/';
        $resolver = new Resolver($template_dir);
        $context = new RequestContext();

        $matcher = new DefaultMatcher($resolver, $context);
        $match = $matcher->match('/invalid');

        $this->assertArrayHasKey('_controller', $match);
        $this->assertArrayHasKey('_route', $match);
        $this->assertEquals(Resolver::NOTFOUND_TEMPLATE, '/' . $match['_route']);
    }

    /**
     * testMatcherNotFoundNoTemplate
     */
    public function testMatcherNotFoundNoTemplate()
    {
        // No 404 template should route to the error controller
        $template_dir = __DIR__ . '/../Fixtures/templates_empty/';
        $resolver = new Resolver($template_dir);
        $context = new RequestContext();

        $matcher = new DefaultMatcher($resolver, $context);
        $match = $matcher->match('/invalid');

        $this->assertArrayHasKey('_controller', $match);
        $this->assertArrayHasKey('_route', $match);
        $this->assertEquals('404', $match['_route']);
    }
}
