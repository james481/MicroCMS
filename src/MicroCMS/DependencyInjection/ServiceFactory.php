<?php

/**
* src/MicroCMS/DependencyInjection/ServiceFactory.php
*
* Container Core Service Factory Class
*
* @author James Watts <jamescwatts[at]gmail[dot]com>
* @copyright (c) 2014 James Watts
* @license MIT
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*
*/

namespace MicroCMS\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerInterface;
use MicroCMS\DependencyInjection\Modules\MonologFactory;
use MicroCMS\DependencyInjection\Modules\TwigFactory;
use MicroCMS\Template\Resolver;

class ServiceFactory
{
    /**
     * buildLogger
     * Build the logger service
     *
     * @return Monolog\Logger $logger
     */
    public static function buildLogger(ContainerInterface $container)
    {
        $factory = new MonologFactory(
            $container->getParameter('kernel.log_dir'),
            $container->getParameter('kernel.env')
        );

        return($factory->getServiceClass());
    }

    /**
     * buildTemplateResolver
     * Build the template resolver service
     *
     * @return MicroCMS\Template\Resolver $resolver
     */
    public static function buildTemplateResolver(ContainerInterface $container)
    {
        $resolver = new Resolver($container->getParameter('kernel.template_dir'));

        return($resolver);
    }

    /**
     * buildTemplating
     * Build the twig templating service
     *
     * @return \Twig_Environment $twig
     */
    public static function buildTemplating(ContainerInterface $container)
    {
        $factory = new TwigFactory(
            $container->getParameter('kernel.template_dir'),
            $container->getParameter('kernel.cache_dir'),
            $container->getParameter('kernel.env')
        );

        return($factory->getServiceClass());
    }
}
