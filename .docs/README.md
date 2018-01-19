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
	Apitte\Console\Command\RouteDumpCommand:
```

## Usage

Let say you would like to get list of all routes from CLI. The only thing you need to run is simple command. 

`php bin/console apitte:route:dump`

