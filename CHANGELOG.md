# Changelog

All notable changes to NSY Framework are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

> Codename **Gamelan** — taken from the traditional Indonesian ensemble, where
> every instrument has a precise role yet the whole plays as one. Same idea
> behind NSY 7.0.0: a small set of focused core components and optional HMVC
> modules, wired together to behave as a single system.

---

## [7.0.0] — 2026-10-02

The first major release after `v6.1.5` (2024-07-08): 70 commits, and a core that
was largely rebuilt. Highlights are the in-app documentation viewer, the logging
system, a security layer with its own test coverage, a PHPUnit suite, and a full
pass to strict types and camelCase.

### Breaking changes

These require action when upgrading from 6.x.

- **Method names are now `camelCase`.** Class methods were `snake_case`
  throughout 6.x and are `camelCase` in 7.0.0, matching PSR-12 and enforced by a
  convention test. Custom code that calls framework methods must be renamed
  (e.g. `$model->get_data()` → `$model->getData()`).
- **Global helper functions stay `snake_case`.** Unchanged from 6.x — this is
  deliberate (`base_url()`, `is_filled()`), and `NamingConventionTest` enforces
  both rules separately.
- **Composer vendor directory moved to `System/Vendor/`.** Dependencies are no
  longer committed to the repository. Run `composer install` after upgrading
  (`--no-dev` in production), then `composer dump-autoload -o`.
- **`System/Vendor/` is gitignored.** Use `composer install` to restore it.
- **Third-party vendor packages were replaced with first-party libraries.**
  `System/Libraries/` now ships its own `File`, `Json`, `Encryption`, `Session`,
  `Request`, `Validate`, `Curl`, `Cookie`, `Docs`, `Markdown`, `Aliases` and
  `LanguageCode`, cutting roughly 37 900 lines of vendored code.
- **Production seeders are not blocked by the framework itself.** The example
  seeder (`Seeder_Example_User`) refuses to run when `APP_ENV=production`; your
  own seeders decide their own policy.
- **Web-triggered migrations are disabled in production.** `NSY_Desk` returns
  `403` when a migration is requested over HTTP while `APP_ENV=production`. Run
  migrations through the CLI (`nsy run:migrate`) instead.

### Added

#### Documentation
- **In-app documentation viewer** at `/docs/{slug}` — renders the 23 `docs/*.md`
  guides through `System\Libraries\Docs` (slug manifest, categories, prev/next
  neighbours) and `System\Libraries\Markdown`. Serves a 404 for unknown slugs.
  `docs/` ships with the release so the guides can be read locally; uploading it
  to your own server is optional and only needed to serve this viewer there.
- Search across the documentation index, an "On this page" table of contents with
  scroll spy, and category grouping.
- 23 guides under `docs/`, including new pages for maintenance mode, seeder,
  class aliases, encryption, documentation viewer, JSON, cookie, curl,
  dependencies, session and validation.
- Deploy guides for shared hosting — Apache (`.htaccess` for document root and
  for whole-project layout) and nginx (`try_files`) server blocks.

#### Logging
- **PSR-3 file logging core** (`System/Libraries/Log`) driven by
  `LogManager`, configurable through `env.php`.
- Error, database, router, view and migration instrumentation.
- Web access to framework internals (logs, storage) is denied via `.htaccess`.

#### Security
- `SecurityMiddleware` with **CSRF**, **XSS sanitisation** and **rate limiting**
  (`rateLimit($bucket, $maxAttempts, $windowSeconds)`), plus origin checking for
  token issuance.
- **SSRF hardening** across the `File` and `Curl` libraries.
- Secure cookies enabled by default.
- URL validation and hardened SQL construction in the query builder.

#### Data & database
- **Seeders and factories** (`nsy make:seeder`, `nsy make:factory`,
  `nsy run:seed [all|list|name]`) with the `NSY_Seeder` runner.
- **HTTP migrations made opt-in** — disabled unless
  `APP_MIGRATION_HTTP_TOKEN` is set and the request matches it (compared with
  `hash_equals`).
- `NSY_QueryBuilder`, `NSY_Paginator` and transaction support
  (`DB::transaction()`) on top of `NSY_DB::connect`.

#### Framework
- **Maintenance mode** with a real `503` response and `Retry-After: 3600`:
  `nsy down [message]` / `nsy up`, `APP_MAINTENANCE`, `APP_MAINTENANCE_ALLOW` for
  IP bypass. Browsers get the 503 template; API clients get JSON.
- **Named routes** (`route('user.show', [5])`) and structured error pages
  (`404`, `500`, `503`).
- **CodeIgniter-ported global helpers** and an asset helper
  (`stringify_attributes()`, `directory_map()` …).
- **Assets helper** — `header_assets()` / `footer_assets()` with
  `Add::link/script/meta` and `?v=filemtime` cache-busting.
- **Configuration cache** and route cache manager.
- **Dark / light theme toggle** with `localStorage` persistence and
  `prefers-color-scheme` detection, applied before first paint.
- `LoadTime` and `LanguageCode` libraries.
- Strict types and return type hints across the Razr engine and core classes.

#### CLI
- Generators: `make:controller`, `make:model`, `make:module`, `make:migrate`,
  `make:route`, `make:view`, `make:middleware`, `make:seeder`, `make:factory`.
- Inspection: `show:module`, `show:controller`, `show:model`, `show:migrate`.
- Runtime: `serve`, `dump:autoload`, `dump:mysql`, `run:migrate`, `run:seed`,
  `down`, `up`, `--setup`, `--install`.
- Input validation for the generators, and generated class listings now filter
  test files.
- Dedicated dev-server router (`.cli/tmp/router.php`) for `nsy serve`.
- CLI **2.0.0**.

#### Tests
- **123 tests / 242 assertions**, across 22 test files:
  - `Core/` — `RouterOptimizedTest`, `QueryBuilderSqlTest`, `PaginatorTest`,
    `SqlHardeningTest`, `ConfigCacheTest`
  - `Libraries/` — `CookieTest`, `CurlTest`, `EncryptionTest`, `FileTest`,
    `JsonTest`, `RequestTest`, `SessionTest`, `ValidateTest`
  - `Helpers/` — `CodeIgniterHelpersTest`, `RouterHelperTest`,
    `GlobalHelpersTest`, `GlobalHelpersMiscTest`
  - `Middlewares/` — `SecurityMiddlewareTest`, `SanitizationTest`
  - `Config/` — `AppConfigTest`, `SiteConfigTest`
  - `Convention/` — `NamingConventionTest`
- `composer test` runs PHPUnit; `composer lint` runs PHP-CS-Fixer in dry-run.

### Changed
- Package reference moved to `vikry/nsy` on Packagist.
- Composer platform pinned to PHP 8.1.0; PHPUnit to 9.6.37.
- `nesbot/carbon` upgraded `2.73.0` → `3.14.2`.
- `nesbot/carbon`, `voku/anti-xss`, `rakit/validation` and `psr/log` are the
  declared runtime dependencies.
- Centralised repository and footer metadata into config-driven helpers
  (`get_repo_url()`, `get_codename()`, `get_since()` …).
- Logging moved to its own configurable core instead of ad-hoc `echo`.
- Site header, HMVC landing page and documentation index redesigned onto one
  shared theme; documentation emoji replaced with inline SVG icons.
- Back-to-top button is now site-wide (shared footer template) rather than
  docs-only.
- **Responsive mobile pass**: search label hidden below 560px (with
  `aria-label` kept), action buttons stack full width, documentation page head
  and badge reflow, documentation sidebar becomes a slide-in drawer behind a
  "Docs menu" button, and the prev/next pager stops forcing horizontal scroll.
- Removed legacy third-party dependency references and `browserconfig.xml` /
  `package-lock.json` leftovers.

### Fixed
- Paginator active-page span now escapes its `class` attribute.
- `number_format_short()` documentation example corrected (`999` → `1 Rb`).
- Error paths in the CLI (`NSY_Desk`) route through the shared handler instead of
  raw `<pre>` output, in both CLI and web contexts.
- Migrations and `DB.php` errors no longer print `<pre>` directly.
- Generated class listings no longer include test fixtures.

### Security
See **Breaking changes** above for the migration/seeder policy changes. Security
work in this release: CSRF + XSS + rate limiting in `SecurityMiddleware`, SSRF
hardening in `File`/`Curl`, secure cookies by default, hardened SQL construction,
opt-in token-gated HTTP migrations, production-blocked web migrations, and denied
web access to logs and storage.

### Dependencies

| Package | Version | Role |
| --- | --- | --- |
| `nesbot/carbon` | `^3.14.1` | Dates and times |
| `voku/anti-xss` | `^4.1` | Input sanitisation |
| `rakit/validation` | `^1.4` | `Validate` library |
| `psr/log` | `^1.1 \|\| ^2.0 \|\| ^3.0` | Logging interface |

Dev-only: `phpunit/phpunit ^9.6`, `fakerphp/faker ^1.24`,
`friendsofphp/php-cs-fixer ^3.95`.

CI runs on PHP 8.1, 8.2, 8.3, 8.4 and 8.5. Dependabot is configured for Composer
and GitHub Actions.

---

## Migration notes for 6.x → 7.0.0

1. `composer install` (dependencies are no longer committed).
2. Rename any custom calls to framework methods from `snake_case` to
   `camelCase`.
3. **Optional:** upload `docs/` to your server if you want the in-app
   documentation viewer (`/docs/{slug}`). It ships in the release so you can read
   the guides locally, but a normal application does not need it at runtime.
4. Add the new keys to `env.php` as needed: `APP_MAINTENANCE`,
   `APP_MAINTENANCE_ALLOW`, `APP_MIGRATION_HTTP_TOKEN`, logging keys.
5. Rotate `ENCRYPTION_KEY` if a 6.x install with the same key ever ran in
   production.
6. Run migrations through the CLI (`nsy run:migrate`), not over HTTP.

---

[7.0.0]: https://github.com/kazuyamarino/nsy/releases/tag/v7.0.0
[Unreleased]: https://github.com/kazuyamarino/nsy/compare/v7.0.0...HEAD