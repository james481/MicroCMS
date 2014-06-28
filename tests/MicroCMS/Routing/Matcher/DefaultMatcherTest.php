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

class DefaultMatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testMatcherHomepageRoute
     */
    public function testMatcherHomepageRoute()
    {
        $template_dir = __DIR__ . '/../Fixtures/templates/';
        $context = new RequestContext();

        $matcher = new DefaultMatcher($template_dir, $context);
        $match = $matcher->match('/');

        $this->assertArrayHasKey('_controller', $match);
        $this->assertArrayHasKey('_template', $match);
        $this->assertEquals(DefaultMatcher::HOME, $match['_template']);
    }

    /**
     * testMatcherHomepageNoTemplate
     */
    public function testMatcherHomepageNoTemplate()
    {
        // If no homepage template is found, we should get 404
        $template_dir = __DIR__ . '/../Fixtures/invalid/';
        $context = new RequestContext();

        $matcher = new DefaultMatcher($template_dir, $context);
        $match = $matcher->match('/');

        $this->assertArrayHasKey('_controller', $match);
        $this->assertArrayNotHasKey('_template', $match);
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
        $context = new RequestContext();

        $matcher = new DefaultMatcher($template_dir, $context);
        $match = $matcher->match('/invalid');

        $this->assertArrayHasKey('_controller', $match);
        $this->assertArrayHasKey('_template', $match);
        $this->assertEquals(DefaultMatcher::NOTFOUND, $match['_template']);
    }

    /**
     * testMatcherNotFoundNoTemplate
     */
    public function testMatcherNotFoundNoTemplate()
    {
        // No 404 template should route to the error controller
        $template_dir = __DIR__ . '/../Fixtures/invalid/';
        $context = new RequestContext();

        $matcher = new DefaultMatcher($template_dir, $context);
        $match = $matcher->match('/invalid');

        $this->assertArrayHasKey('_controller', $match);
        $this->assertArrayNotHasKey('_template', $match);
        $this->assertArrayHasKey('_route', $match);
        $this->assertEquals('404', $match['_route']);
    }
}
