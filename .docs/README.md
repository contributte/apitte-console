# Apitte Console

Console commands for [Apitte](https://github.com/apitte/core).

## Setup

First of all, setup [core](https://github.com/apitte/core) package.

Install and register console plugin

```bash
composer require apitte/console
```

```yaml
api:
    plugins: 
        Apitte\Console\DI\ConsolePlugin:
```

You also need setup an integration of [symfony/console](https://symfony.com/doc/current/components/console.html), try [contributte/console](https://github.com/contributte/console/)

If you use [kdyby/console](https://github.com/Kdyby/Console) then make sure you add required tag to registered commands.

```yaml
decorator:
    Symfony\Component\Console\Command\Command:
        tags: [kdyby.console.command]
```

## Commands

### Route dump

List all endpoints and their details

```bash
php bin/console apitte:route:dump
```
