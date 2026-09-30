# NSY Dependencies — Composer Packages

NSY intentionally ships a **lean dependency tree**: only **4 runtime** packages
and **3 development** packages are declared directly. Everything else is pulled
in transitively by Composer.

`System/Vendor/` is **not** committed, so the repository stays small and release
archives never ship Composer packages. Dependencies are restored with
`composer install` (see the Quick Start in `README.md`).

> The framework's own helpers (`Json`, `Cookie`, `Encryption`, `Session`,
> `Curl`, `Validator`, …) are **native** classes in `System/Libraries` and do
> not appear in this list. Each has its own document.

## Table of Contents

1. [Install & keep it small](#install--keep-it-small)
2. [Runtime dependencies](#runtime-dependencies)
3. [Development dependencies](#development-dependencies)
4. [Transitive dependencies](#transitive-dependencies)
5. [Quick Reference](#quick-reference)

## Install & keep it small

| Command | Use |
| --- | --- |
| `composer install` | Development / first checkout (includes dev tools) |
| `composer install --no-dev --optimize-autoloader` | Production — only the 17 runtime packages |
| `composer show` | List every installed package |
| `composer show --direct` | List only the directly declared packages |

`System/Vendor/` is gitignored; `composer.lock` **is** committed so installs are
reproducible. A fresh release download therefore needs one `composer install`
before the app can boot (`public/index.php` loads `System/Vendor/autoload.php`).

## Runtime dependencies

Shipped to production.

| Package | Version | License | Purpose |
| --- | --- | --- | --- |
| `nesbot/carbon` | 2.73.0 | MIT | An API extension for DateTime that supports 281 different languages. |
| `voku/anti-xss` | 4.1.44 | MIT | anti xss-library |
| `rakit/validation` | 1.4.0 | MIT | PHP Laravel like standalone validation library |
| `psr/log` | 3.0.2 | MIT | Common interface for logging libraries |

How NSY uses them:

- **`nesbot/carbon`** — powers the date helpers such as `get_today()` and
  `get_year()` (`Carbon::now()->isoFormat(...)`). Available globally.
- **`voku/anti-xss`** — used by `System/Middlewares/SecurityMiddleware.php`
  (`voku\helper\AntiXSS`) to sanitize untrusted input; see
  [Security Middleware](README_SECURITY_MIDDLEWARE.md).
- **`rakit/validation`** — exposed as `System\Libraries\Validator`; see
  [Validation](README_VALIDATION.md).
- **`psr/log`** — the `LoggerInterface` contract implemented by
  `System/Libraries/Log/Logger.php`; see [Logging](README_LOGGING.md).

## Development dependencies

Never shipped to production (`composer install --no-dev` skips them).

| Package | Version | License | Purpose |
| --- | --- | --- | --- |
| `phpunit/phpunit` | 9.6.37 | BSD-3-Clause | The PHP Unit Testing framework. |
| `fakerphp/faker` | 1.24.1 | MIT | Faker is a PHP library that generates fake data for you. |
| `friendsofphp/php-cs-fixer` | 3.95.27 | MIT | A tool to automatically fix PHP code style |

How to use them inside NSY:

- **`phpunit/phpunit`** — run the suite with `composer test`
  (`System/Test/`, config `phpunit.xml`). Includes the naming-convention gate.
- **`fakerphp/faker`** — generate fake rows/seeds in development, e.g.
  `Faker\Factory::create()->name()`. Not used at runtime.
- **`friendsofphp/php-cs-fixer`** — `composer lint` (check), `composer lint:fix`
  (apply the enforced rules), `composer lint:full` (one-time PSR-12 + tabs
  normalisation).

## Transitive dependencies

Installed automatically by Composer; you normally never reference them
directly. Listed for completeness (versions follow `composer.lock`).

**Runtime (13):**

| Package | Version | License | Purpose |
| --- | --- | --- | --- |
| `carbonphp/carbon-doctrine-types` | 3.2.1 | MIT | Types to use Carbon in Doctrine |
| `psr/clock` | 1.0.0 | MIT | Common interface for reading the clock. |
| `symfony/deprecation-contracts` | 3.7.1 | MIT | A generic function and convention to trigger deprecation notices |
| `symfony/polyfill-iconv` | 1.37.0 | MIT | Symfony polyfill for the Iconv extension |
| `symfony/polyfill-intl-grapheme` | 1.41.0 | MIT | Symfony polyfill for intl's grapheme_* functions |
| `symfony/polyfill-intl-normalizer` | 1.42.0 | MIT | Symfony polyfill for intl's Normalizer class and related functions |
| `symfony/polyfill-mbstring` | 1.38.2 | MIT | Symfony polyfill for the Mbstring extension |
| `symfony/polyfill-php72` | 1.31.0 | MIT | Symfony polyfill backporting some PHP 7.2+ features to lower PHP versions |
| `symfony/polyfill-php80` | 1.37.0 | MIT | Symfony polyfill backporting some PHP 8.0+ features to lower PHP versions |
| `symfony/translation` | 6.4.44 | MIT | Provides tools to internationalize your application |
| `symfony/translation-contracts` | 3.7.1 | MIT | Generic abstractions related to translation |
| `voku/portable-ascii` | 2.1.1 | MIT | Portable ASCII library - performance optimized (ascii) string functions for php. |
| `voku/portable-utf8` | 6.1.1 | Apache-2.0 or GPL-2.0 | Portable UTF-8 library - performance optimized (unicode) string functions for php. |

**Development (56):**

| Package | Version | License | Purpose |
| --- | --- | --- | --- |
| `clue/ndjson-react` | 1.3.0 | MIT | Streaming newline-delimited JSON (NDJSON) parser and encoder for ReactPHP. |
| `composer/pcre` | 3.4.0 | MIT | PCRE wrapping library that offers type-safe preg_* replacements. |
| `composer/semver` | 3.5.0 | MIT | Version comparison library that offers utilities, version constraint parsing and validation. |
| `composer/xdebug-handler` | 3.0.5 | MIT | Restarts a process without Xdebug. |
| `doctrine/instantiator` | 2.0.0 | MIT | A small, lightweight utility to instantiate objects in PHP without invoking their constructors |
| `ergebnis/agent-detector` | 1.2.0 | MIT | Provides a detector for detecting the presence of an agent. |
| `evenement/evenement` | 3.0.2 | MIT | Événement is a very simple event dispatching library for PHP |
| `fidry/cpu-core-counter` | 1.4.1 | MIT | Tiny utility to get the number of CPU cores. |
| `myclabs/deep-copy` | 1.14.0 | MIT | Create deep copies (clones) of your objects |
| `nikic/php-parser` | 5.9.0 | BSD-3-Clause | A PHP parser written in PHP |
| `phar-io/manifest` | 2.0.4 | BSD-3-Clause | Component for reading phar.io manifest information from a PHP Archive (PHAR) |
| `phar-io/version` | 3.2.1 | BSD-3-Clause | Library for handling version information and constraints |
| `phpunit/php-code-coverage` | 9.2.32 | BSD-3-Clause | Library that provides collection, processing, and rendering functionality for PHP code coverage information. |
| `phpunit/php-file-iterator` | 3.0.6 | BSD-3-Clause | FilterIterator implementation that filters files based on a list of suffixes. |
| `phpunit/php-invoker` | 3.1.1 | BSD-3-Clause | Invoke callables with a timeout |
| `phpunit/php-text-template` | 2.0.4 | BSD-3-Clause | Simple template engine. |
| `phpunit/php-timer` | 5.0.3 | BSD-3-Clause | Utility class for timing |
| `psr/container` | 2.0.2 | MIT | Common Container Interface (PHP FIG PSR-11) |
| `psr/event-dispatcher` | 1.0.0 | MIT | Standard interfaces for event handling. |
| `react/cache` | 1.2.0 | MIT | Async, Promise-based cache interface for ReactPHP |
| `react/child-process` | 0.6.7 | MIT | Event-driven library for executing child processes with ReactPHP. |
| `react/dns` | 1.14.0 | MIT | Async DNS resolver for ReactPHP |
| `react/event-loop` | 1.6.0 | MIT | ReactPHP's core reactor event loop that libraries can use for evented I/O. |
| `react/promise` | 3.3.0 | MIT | A lightweight implementation of CommonJS Promises/A for PHP |
| `react/socket` | 1.17.0 | MIT | Async, streaming plaintext TCP/IP and secure TLS socket server and client connections for ReactPHP |
| `react/stream` | 1.4.0 | MIT | Event-driven readable and writable streams for non-blocking I/O in ReactPHP |
| `sebastian/cli-parser` | 1.0.2 | BSD-3-Clause | Library for parsing CLI options |
| `sebastian/code-unit` | 1.0.8 | BSD-3-Clause | Collection of value objects that represent the PHP code units |
| `sebastian/code-unit-reverse-lookup` | 2.0.3 | BSD-3-Clause | Looks up which function or method a line of code belongs to |
| `sebastian/comparator` | 4.0.10 | BSD-3-Clause | Provides the functionality to compare PHP values for equality |
| `sebastian/complexity` | 2.0.3 | BSD-3-Clause | Library for calculating the complexity of PHP code units |
| `sebastian/diff` | 4.0.6 | BSD-3-Clause | Diff implementation |
| `sebastian/environment` | 5.1.5 | BSD-3-Clause | Provides functionality to handle HHVM/PHP environments |
| `sebastian/exporter` | 4.0.9 | BSD-3-Clause | Provides the functionality to export PHP variables for visualization |
| `sebastian/global-state` | 5.0.8 | BSD-3-Clause | Snapshotting of global state |
| `sebastian/lines-of-code` | 1.0.4 | BSD-3-Clause | Library for counting the lines of code in PHP source code |
| `sebastian/object-enumerator` | 4.0.4 | BSD-3-Clause | Traverses array structures and object graphs to enumerate all referenced objects |
| `sebastian/object-reflector` | 2.0.4 | BSD-3-Clause | Allows reflection of object attributes, including inherited and non-public ones |
| `sebastian/recursion-context` | 4.0.7 | BSD-3-Clause | Provides functionality to recursively process PHP variables |
| `sebastian/resource-operations` | 3.0.4 | BSD-3-Clause | Provides a list of PHP built-in functions that operate on resources |
| `sebastian/type` | 3.2.1 | BSD-3-Clause | Collection of value objects that represent the types of the PHP type system |
| `sebastian/version` | 3.0.2 | BSD-3-Clause | Library that helps with managing the version number of Git-hosted PHP projects |
| `symfony/console` | 6.4.47 | MIT | Eases the creation of beautiful and testable command line interfaces |
| `symfony/event-dispatcher` | 6.4.44 | MIT | Provides tools that allow your application components to communicate with each other by dispatching events and listening to them |
| `symfony/event-dispatcher-contracts` | 3.7.1 | MIT | Generic abstractions related to dispatching event |
| `symfony/filesystem` | 6.4.45 | MIT | Provides basic utilities for the filesystem |
| `symfony/finder` | 6.4.47 | MIT | Finds files and directories via an intuitive fluent interface |
| `symfony/options-resolver` | 6.4.30 | MIT | Provides an improved replacement for the array_replace PHP function |
| `symfony/polyfill-ctype` | 1.37.0 | MIT | Symfony polyfill for ctype functions |
| `symfony/polyfill-php81` | 1.38.1 | MIT | Symfony polyfill backporting some PHP 8.1+ features to lower PHP versions |
| `symfony/polyfill-php84` | 1.38.1 | MIT | Symfony polyfill backporting some PHP 8.4+ features to lower PHP versions |
| `symfony/process` | 6.4.46 | MIT | Executes commands in sub-processes |
| `symfony/service-contracts` | 3.7.3 | MIT | Generic abstractions related to writing services |
| `symfony/stopwatch` | 6.4.24 | MIT | Provides a way to profile code |
| `symfony/string` | 6.4.46 | MIT | Provides an object-oriented API to strings and deals with bytes, UTF-8 code points and grapheme clusters in a unified way |
| `theseer/tokenizer` | 1.3.1 | BSD-3-Clause | A small library for converting tokenized PHP source code into XML and potentially other formats |

## Quick Reference

| Task | Command |
| --- | --- |
| Install (development) | `composer install` |
| Install (production) | `composer install --no-dev --optimize-autoloader` |
| List packages | `composer show` |
| List direct packages only | `composer show --direct` |
| Validate manifest | `composer validate` |
| Run tests | `composer test` |
| Style check | `composer lint` |
| Style fix | `composer lint:fix` |

Related: [Logging](README_LOGGING.md) · [Validation](README_VALIDATION.md) ·
[Security Middleware](README_SECURITY_MIDDLEWARE.md) · [Overview](OVERVIEW.md).
