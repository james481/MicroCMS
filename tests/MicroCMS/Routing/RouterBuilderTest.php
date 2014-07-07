<?php

/**
* tests/MicroCMS/Routing/RouterBuilderTest.php
*
* MicroCMS Router Builder Test
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\Routing;

use Symfony\Component\Config\FileLocator;
use MicroCMS\Template\Resolver;

class RouterBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testRouterBuilds
     */
    public function testRouterBuilds()
    {
        // Call the RouterBuilder with no arguments,
        // and make sure we get back a Router with a default matcher.

        $resolver = new Resolver();
        $builder = new RouterBuilder($resolver);
        $router = $builder->prepareRouter();
        $this->assertInstanceOf('MicroCMS\Routing\Router', $router);
        $matchers = $this->getRouterMatchers($router);
        $this->assertEquals(1, count($matchers));
        $this->assertInstanceOf('MicroCMS\Routing\Matcher\DefaultMatcher', $matchers[0]);

    }

    /**
     * testRouterBuildsTemplateMatcher
     */
    public function testRouterBuildsTemplateMatcher()
    {
        // Call the RouterBuilder with a valid template dir,
        // and make sure the Router get built with the template matcher.
        $resolver = new Resolver(__DIR__ . '/Fixtures/templates/');
        $builder = new RouterBuilder($resolver);
        $router = $builder->prepareRouter();

        $this->assertInstanceOf('MicroCMS\Routing\Router', $router);
        $matchers = $this->getRouterMatchers($router);
        $this->assertEquals(2, count($matchers));
        $this->assertInstanceOf('MicroCMS\Routing\Matcher\TemplateMatcher', $matchers[0]);

    }

    /**
     * testIgnoresInvalidTemplateDir
     */
    public function testIgnoresInvalidTemplateDir()
    {
        // If we call the RouterBuilder with an empty template directory,
        // it shouldn't build the template matcher.
        $resolver = new Resolver(__DIR__ . '/Fixtures/templates_empty/');
        $builder = new RouterBuilder($resolver);
        $router = $builder->prepareRouter();

        $this->assertInstanceOf('MicroCMS\Routing\Router', $router);
        $matchers = $this->getRouterMatchers($router);
        $this->assertEquals(1, count($matchers));
        $this->assertInstanceOf('MicroCMS\Routing\Matcher\DefaultMatcher', $matchers[0]);

    }

    /**
     * testRouterBuildsSymfonyMatcher
     */
    public function testRouterBuildsSymfonyMatcher()
    {
        // Call the RouterBuilder with a valid config locator,
        // and make sure the Symfony URL matcher is built.
        $locator = new FileLocator(__DIR__ . '/Fixtures/config/');
        $resolver = new Resolver();
        $builder = new RouterBuilder($resolver, $locator, 'test');
        $router = $builder->prepareRouter();

        $this->assertInstanceOf('MicroCMS\Routing\Router', $router);
        $matchers = $this->getRouterMatchers($router);
        $this->assertEquals(2, count($matchers));
        $this->assertInstanceOf('Symfony\Component\Routing\Matcher\UrlMatcher', $matchers[0]);

    }

    /**
     * testIgnoresInvalidConfigDir
     */
    public function testIgnoresInvalidConfigDir()
    {
        // Call the RouterBuilder with an invalid config locator,
        // and make sure the Symfony URL matcher is not built.
        $resolver = new Resolver();
        $locator = new FileLocator(__DIR__ . '/Fixtures/invalid/');
        $builder = new RouterBuilder($resolver, $locator, 'test');
        $router = $builder->prepareRouter();

        $this->assertInstanceOf('MicroCMS\Routing\Router', $router);
        $matchers = $this->getRouterMatchers($router);
        $this->assertEquals(1, count($matchers));
        $this->assertInstanceOf('MicroCMS\Routing\Matcher\DefaultMatcher', $matchers[0]);

    }

    /**
     * getRouterMatchers
     * Convenience method for getting the protected
     * matchers stack from a router by reflection.
     *
     * @param MicroCMS\Routing\Router $router
     * @return array $matchers
     */
    private function getRouterMatchers(Router $router)
    {
        $ref = new \ReflectionClass($router);
        $matchers_ref = $ref->getProperty('matchers');
        $matchers_ref->setAccessible(true);
        $matchers = $matchers_ref->getValue($router);

        return($matchers);
    }
}
