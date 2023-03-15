Behatch contexts
================

[![Build status](https://github.com/svetel/behatch-contexts/actions/workflows/php.yml/badge.svg?branch=master)](https://github.com/svetel/behatch-contexts/actions/workflows/php.yml)
[![StandWithUkraine](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/badges/StandWithUkraine.svg)](https://github.com/vshymanskyy/StandWithUkraine/blob/main/docs/README.md)
[![Stand With Ukraine](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/banner2-direct.svg)](https://vshymanskyy.github.io/StandWithUkraine/)
Behatch contexts provide most common Behat tests.

This project forked from [Behatch/contexts](https://github.com/Behatch/contexts)

- Added support php 8.1
- Fixed deprecations
- Fixed tests for all tags, except:
  - @user
  - @skip
  - @javascript

Welcome to contribute

Installation
------------

This extension requires:

* Behat 3+
* Mink
* Mink extension

### Project dependency

1. [Install Composer](https://getcomposer.org/download/)
2. Require the package with Composer:

```
$ composer require --dev svetel/behatch-contexts
```

3. Activate extension by specifying its class in your `behat.yml`:

```yaml
# behat.yml
default:
    # ...
    extensions:
        Behatch\Extension: ~
```

### Project bootstrapping

1. Download the Behatch skeleton with composer:

```
$ php composer.phar create-project behatch/skeleton
```

Browser, json, table and rest step need a mink configuration, see [Mink
extension](https://github.com/Behat/MinkExtension) for more information.

Usage
-----

In `behat.yml`, enable desired contexts:

```yaml
default:
    suites:
        default:
            contexts:
                - behatch:context:browser
                - behatch:context:debug
                - behatch:context:system
                - behatch:context:json
                - behatch:context:table
                - behatch:context:rest
                - behatch:context:xml
```

### Examples

This project is self-tested, you can explore the [features
directory](./tests/features) to find some examples.

Configuration
-------------

* `browser` - more browser related steps (like mink)
    * `timeout` - default timeout
* `debug` - helper steps for debugging
    * `screenshotDir` - the directory where store screenshots
* `system` - shell related steps
    * `root` - the root directory of the filesystem
* `json` - JSON related steps
    * `evaluationMode` - javascript "foo.bar" or php "foo->bar"
* `table` - play with HTML the tables
* `rest` - send GET, POST, ... requests and test the HTTP headers
* `xml` - XML related steps

### Configuration Example

For example, if you want to change default directory to screenshots - you can do it this way:

```yaml
default:
    suites:
        default:
            contexts:
                - behatch:context:debug:
                    screenshotDir: "var"
```
