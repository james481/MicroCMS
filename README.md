# MicroCMS - Micro framework for CMS projects

This is a lightweight and easy to use framework for the development of small CMS-like projects. It is designed for rapid prototyping of a basic application which may later be expanded onto a larger framework (Symfony). The typical use case would be a project that is very limited in initial scope, but a larger and more complex application is planned for the future.

## MicroCMS provides the following features:

## Routing

The MicroCMS router implements a stack of request matchers, which are checked in order to match a request:

* __Symfony Matcher__ - If an environment specific routing file is provided (usually `app/config/routing_{env}.yml`), any routes specified in that file (in the usual Symfony manner) will be matched first. Routes to custom controllers can be specified by a fully qualified class name and action (`\\Namespace\\Pkg\\Class::indexAction`).

* __Template Matcher__ - Any Twig template with the .html extension in the application templates directory (usually `app/templates`) **whose filename does not begin with `_`** will be automatically routed by name, either with or without extension. In other words, `app/templates/foo.html` will be rendered by requests to either `/foo` or `/foo.html`. Templates can be nested in directories (`/foo/bar` matches `app/templates/foo/bar.html`), and you can use the `_` prefix for templates that you don't want to be routable (for twig template includes or inheritance, etc).

* __Default Matcher__ - The default matcher provides routes for the homepage and error pages. If a file named `index.html` exists in the application templates directory, it will be rendered for the site homepage (as well as `/index` and `/index.html`). In addition, the Default Matcher will render `_404.html` and `_500.html` for not found and application errors. Note that the `_500.html` template is only used in the production environment (other environments will display a stack trace for the exception / error).

## DI Container

The framework builds a Symfony DI Container, which will be provided to any custom controllers automatically as long as they implement `ContainerAwareInterface`. The container can be customized by editing the environment specific configuration file (usually `app/config/app_{env}.yml`) and specifying custom services / factories.

## Twig Templating

The DI Container includes a Twig renderer which is setup by the kernel.

## Logging

The kernel also builds a Monolog logger, which will log to environment specific log files in the application logs directory (usually `app/logs`). In addition to being used internally by the framework, a [PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) compatible trait (`MicroCMS\DependencyInjection\LogAwareTrait`) can also be used by custom classes for convenient logging.

# About

The framework is currently at version `0.1.0`. It is functionally fairly complete and tested, but major additions are planned for future versions.

The framework is implemented using [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) coding standards, and in the [PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md) namespace standard.

## Documentation

The framework includes [PHPDocumentor](http://www.phpdoc.org/) compatible comments for all source files. The API documentation is not included in this package but can be built with `phpdoc` in the standard fashion:

```
$ cd /Path/To/MicroCMS
$ phpdoc -d ./src -t ./docs
```

## Tests

The framework includes unit tests for the [PHPunit](http://phpunit.de/) test framework, and can be run by:

```
$ cd /Path/To/MicroCMS
$ composer install
$ phpunit
```

## TODO

Version `0.2.0` will include:

* More extensive configuration of framework behavior for applications

* Basic content storage and injection into rendered templates

## Author

James Watts - <jamescwatts@gmail.com>

## License

MicroCMS is licensed under the MIT License - see the `LICENSE` file for details
