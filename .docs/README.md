# Apitte\Console

## Content

- [Installation - how to register a plugin](#installation)
- [Configuration - all options](#configuration)
- [Usage - controller showtime](#usage)

## Installation

This plugin requires [Apitte/Core](https://github.com/apitte/core) library.

At first you have to register the main extension.

```yaml
extensions:
    api: Apitte\Core\DI\ApiExtension
```

Secondly, add the `ConsolePlugin` plugin.

```yaml
api:
    plugins: 
        Apitte\Console\DI\ConsolePlugin:
```

## Configuration

```yaml
api:
    plugins: 
        Apitte\Console\DI\ConsolePlugin:
        
services:
    - Apitte\Console\Command\RouteDumpCommand
```

## Usage

To execute prepared commands you will need Symfony Console Application. Don't waste your time to implement it
and use [prepared solution](#console) from Contributte/Console.

With Contributte/Console you could call `bin/console` and see the magic.

```
php bin/console apitte:<>
```

| Command | Description |
|---------|-------------|
| apitte:route:dump | List all endpoints and their details |

## Console

Take a look at [Contributte/Console](https://github.com/contributte/console).

Install it via composer.

```
composer require contributte/console
```

And setup your NEON.

```
extensions:
    console: Contributte\Console\DI\ConsoleExtension
```

At least create `<project>/bin/console` and make it executable.

```
#!/usr/bin/env php
<?php

/** @var Nette\DI\Container $container */
$container = require __DIR__ . '/../app/bootstrap.php';

// Get application from DI container.
$application = $container->getByType(Contributte\Console\Application::class);

// Run application.
exit($application->run());
```
