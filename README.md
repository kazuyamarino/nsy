# NSY Framework
Simple. Layered. Harmony in MVC and HMVC.

[![PHP >=8.1](https://img.shields.io/badge/PHP-%3E%3D8.1-777BB4)](https://www.php.net/) [![License: MIT](https://img.shields.io/badge/License-MIT-green)](LICENSE.txt) [![PSR-4](https://img.shields.io/badge/PSR--4-Autoloading-blue)](https://www.php-fig.org/psr/psr-4/)

**Site:** [https://nsyframework.com/](https://nsyframework.com/) · **Docs:** [`OVERVIEW`](docs/OVERVIEW.md) · [`Libraries`](docs/README_LIBRARIES.md) · [`Load & Asset`](docs/README_LOAD_AND_ASSETMANAGER.md) · [`Helpers`](docs/README_HELPERS_GLOBAL.md) · [`Router`](docs/README_NSY_ROUTER.md) · [`Migration`](docs/README_MIGRATION.md) · [`Model`](docs/README_MODEL.md)

## Codename
> **Current Release Codename: Gamelan**  
> Inspired by the traditional Indonesian ensemble, **Gamelan** represents harmony, modularity, and synchronized execution. Just as each instrument in a Gamelan orchestra plays a precise role to create a rich rhythm, NSY brings together lightweight core components and flexible modules (HMVC) into a unified, harmonious workflow.  
> *(Note: Codename evolves with major version releases to honor Indonesian cultural heritage).*

## Features
- MVC & HMVC with Razr templates
- PSR-4 Autoloading + NSY CLI (`nsy make:*`, `nsy run:migrate`)
- Asset Manager (`Add::link/script/meta`) with `?v=filemtime` cache-busting
- Unified DB (`NSY_DB::connect`) + powerful Migrations (DRY, quoted identifiers)

## Requirements
- PHP >= 8.1, Composer, MySQL/MariaDB/PostgreSQL/SQL Server (optional)

## Quick Start
```bash
composer create-project kazuyamarino/nsy my-app
cd my-app
cp docs/env.example/env.example.php env.php
composer dump-autoload -o
nsy --setup
```

## Configuration
- `env.php` — `APP_ENV`, `PUBLIC_DIR`, `SITE_TITLE`, `CSRF_TOKEN`, `DB_*` (see `docs/env.example/env.example.php`)
- `System/Config/App.php` & `Site.php` — env-aware with `config_env() ?? fallback` (no file edit in production)
- `System/Config/Mimes.php` — 182 mime types (modern: webp, avif, woff2, wasm)

## Documentation
- [Overview](docs/OVERVIEW.md) — Composer, Config, Helpers, Routes, MVC/HMVC, Assets, PSR-4, CLI
- [Deploy to Shared Hosting](docs/README_DEPLOY_HOSTING.md) — `System/` outside `public_html`, writable dirs, `mod_rewrite`/`AllowOverride`
- [Libraries](docs/README_LIBRARIES.md) — File, LanguageCode, LoadTime, Request, Validate
- [JSON](docs/README_JSON.md) — `Json::read()/write()` (atomic file I/O)
- [Cookie](docs/README_COOKIE.md) — `Cookie::set()/get()` (secure defaults)
- [Encryption](docs/README_ENCRYPTION.md) — `Encryption::encrypt()/decrypt()` (AES-256-GCM)
- [Session](docs/README_SESSION.md) — `Session::start()/get()/flash()`
- [Curl](docs/README_CURL.md) — `Curl::get()/post()` (ext-curl, JSON auto-decode)
- [Load & Asset Manager](docs/README_LOAD_AND_ASSETMANAGER.md) — `Load::view/template/model` & `Add::` (`?v=filemtime`)
- [Helpers Global](docs/README_HELPERS_GLOBAL.md) — `base_url()`, `is_filled()`, `css_url()` etc.
- [CodeIgniter Helpers](docs/README_CODEIGNITER_HELPERS.md) — `stringify_attributes()`, `directory_map()` etc.
- [Router](docs/README_NSY_ROUTER.md) — `Route::get/post/group`, middleware
- [Security Middleware](docs/README_SECURITY_MIDDLEWARE.md) — CSRF, XSS, rate-limit
- [Migration](docs/README_MIGRATION.md) — `Mig::createTable()`, `quoteIdent`/`execDDL` (DRY)
- [Model & DB](docs/README_MODEL.md) — `DB::query()`, `NSY_DB::connect()` unified
- [Query Builder](docs/README_QUERY_BUILDER.md) — `qb('users')->whereIn()->paginate()` — minimal lines

## License
MIT — see [LICENSE.txt](LICENSE.txt)
