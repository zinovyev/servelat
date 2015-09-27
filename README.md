# servelat
Servelat is a way to do things async with the PHP.

[![Build Status](https://travis-ci.org/zinovyev/servelat.svg)](https://travis-ci.org/zinovyev/servelat)
[![Code Climate](https://codeclimate.com/github/zinovyev/servelat/badges/gpa.svg)](https://codeclimate.com/github/zinovyev/servelat)
[![Coverage Status](https://coveralls.io/repos/zinovyev/servelat/badge.svg?branch=master&service=github)](https://coveralls.io/github/zinovyev/servelat?branch=master)

## Table of contents

- [About](## About)
- [Installation](## Installation)
- [Usage](## Usage)
- [Examples](## Examples)
- [Extending](## Extending)

## About
In work...

## Installation
In work...

## Usage
In work...

## Examples
In work...

## Extending
The Servelat project code is pretty customizable and can be easily extended by adding some components.
Take a look at the base `Servelat\ServelatApplication` class. It has a method `ServleatApplication::registerComponents()` which is actually just a wrapper for the `Servelat\Base\AbstractApplication::setComponents()` method of the main `AbstractApplication` class.

It contains a list of component class instantiations and that's it.

So, what actually the Components are. The Components are implementations of the `Servelat\Base\ComponentInterface`, or an extended interface or class.

Each component contains the `ComponentInterface::register()` method where the instance of currently running `AbstractApplication` inheritor is passed to.

The application provides three main features to the components:
* The Dependency Injection Container (available through the `AbstractApplication::getContainer()` method) provides the ability to register and obtain services project-wide. Currently used container is mostly based on a Pimple container (see [the Pimple home page](http://pimple.sensiolabs.org/) for more information);
* The Event Dispatcher (available through the `AbstractApplication::getDispatcher()` method) implements the [Mediator](https://en.wikipedia.org/wiki/Mediator_pattern) pattern to make your projects truly extensible. With help of the Event Dispatcher you can easily add some methods before or after some actions. The description of the base events can be found in the `Servelat\ServelatEvents` final class. Currently used dispatcher is a Symfony2 EventDispatcher Component (see [the EventDispatcher Component home page](http://symfony.com/doc/current/components/event_dispatcher/introduction.html) for more information);
* The Configurator (available through the `AbstractApplication::getConfigurator()` method) is simply the instance of the default SPL [`ArrayObject`](http://php.net/manual/en/class.arrayobject.php) class and allows you to store your configurations project-wide;

Each component have access to all of the three application's methods. You can see the base components (`Servelat\Components\ProcessManagerComponent`, `Servelat\Components\Queues`, `Servelat\Components\TaskManager` etc.) to see what you can do with all this features.

That's a simple code from the `ProcessManagerComponent` which shows you how to register your class as a service in the container and as a listener via the event dispatcher:

```php

$dispatcher = $application->getDispatcher();
$container = $application->getContainer();

// Register process manger service
$container['process_manager'] = function ($c) use ($dispatcher) {
    return new ProcessManager($c['queues.default_queue'], $dispatcher);
};

// Register process manager as listener
$dispatcher->addListener(
    ServelatEvents::TASK_MANAGER_AFTER_PROCESS_TASK,
    [$container['process_manager'], 'onAfterProcessTask'],
    10
);

```
