<?php

/**
* tests/MicroCMS/Template/ResolverTest.php
*
* Test the MicroCMS Template Resolver.
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace MicroCMS\Template;

class ResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testResolverBuilds
     */
    public function testResolverBuilds()
    {
        $template_dir = __DIR__ . '/../Routing/Fixtures/templates/';
        $resolver = new Resolver($template_dir);
        $this->assertInstanceOf('MicroCMS\Template\Resolver', $resolver);
        $this->assertTrue($resolver->hasRoutableTemplates());

        return($resolver);
    }

    /**
     * testResolverIndex
     *
     * @depends testResolverBuilds
     */
    public function testResolverIndex(Resolver $resolver)
    {
        $index_template = $resolver->resolveIndexTemplate();
        $this->assertInstanceOf('MicroCMS\Template\Template', $index_template);
        $this->assertEquals(Resolver::INDEX_TEMPLATE, '/' . $index_template->getRenderName());

        return($resolver);
    }

    /**
     * testResolverNotFound
     *
     * @depends testResolverIndex
     */
    public function testResolverNotFound(Resolver $resolver)
    {
        $nf_template = $resolver->resolveNotFoundTemplate();
        $this->assertInstanceOf('MicroCMS\Template\Template', $nf_template);
        $this->assertEquals(Resolver::NOTFOUND_TEMPLATE, '/' . $nf_template->getRenderName());

        return($resolver);
    }

    /**
     * testResolverTemplates
     *
     * @depends testResolverNotFound
     */
    public function testResolverTemplates(Resolver $resolver)
    {
        $template = $resolver->resolveTemplate('/test');
        $this->assertInstanceOf('MicroCMS\Template\Template', $template);
        $this->assertEquals('test.html', $template->getRenderName());

        $template = $resolver->resolveTemplate('/test.html');
        $this->assertInstanceOf('MicroCMS\Template\Template', $template);
        $this->assertEquals('test.html', $template->getRenderName());

        $template = $resolver->resolveTemplate('/subdir/test');
        $this->assertInstanceOf('MicroCMS\Template\Template', $template);
        $this->assertEquals('subdir/test.html', $template->getRenderName());

        $template = $resolver->resolveTemplate('/subdir/test.html');
        $this->assertInstanceOf('MicroCMS\Template\Template', $template);
        $this->assertEquals('subdir/test.html', $template->getRenderName());

        $template = $resolver->resolveTemplate('/test?foo=bar&foo2=baz');
        $this->assertInstanceOf('MicroCMS\Template\Template', $template);
        $this->assertEquals('test.html', $template->getRenderName());

        $template = $resolver->resolveTemplate('/test.html/foo/bar/foo2/baz');
        $this->assertInstanceOf('MicroCMS\Template\Template', $template);
        $this->assertEquals('test.html', $template->getRenderName());

        $template = $resolver->resolveTemplate('/invalid');
        $this->assertFalse($template);

        $template = $resolver->resolveTemplate('/invalid.html');
        $this->assertFalse($template);
    }

    /**
     * testResolverNoDirectory
     */
    public function testResolverNoDirectory()
    {
        $resolver = new Resolver();
        $this->assertFalse($resolver->hasRoutableTemplates());
        $this->assertFalse($resolver->resolveIndexTemplate());
        $this->assertFalse($resolver->resolveNotFoundTemplate());
        $this->assertFalse($resolver->resolveTemplate('/test'));
    }

    /**
     * testResolverEmptyDirectory
     */
    public function testResolverEmptyDirectory()
    {
        $template_dir = __DIR__ . '/../Routing/Fixtures/templates_empty/';
        $resolver = new Resolver($template_dir);
        $this->assertFalse($resolver->hasRoutableTemplates());
        $this->assertFalse($resolver->resolveIndexTemplate());
        $this->assertFalse($resolver->resolveNotFoundTemplate());
        $this->assertFalse($resolver->resolveTemplate('/test'));
    }
}
