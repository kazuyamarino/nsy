# Deploying NSY to Shared Hosting — `System/` outside `public_html`

> **Scope:** shared / cPanel-style hosting where the document root is
> `public_html`, and the framework (`System/`, `env.php`) must be kept **outside**
> the web root.
>
> Locally NSY runs as `project_root/{public, System, env.php}`. On hosting the
> same layout applies — only the web folder is renamed to `public_html` and moved
> so that `System/` is no longer web-reachable.

---

## Why this works (no `index.php` edit needed)

NSY never hard-codes absolute paths. Every internal path is resolved **relative to
the physical location of `System/`**:

| Loaded by | Expression | Resolves to |
| --- | --- | --- |
| `public/index.php` | `__DIR__ . '/../' . ENV_FILE` | `env.php`, sibling of the web folder |
| `public/index.php` | `__DIR__ . '/../System/Vendor/autoload.php'` | `System/`, sibling of the web folder |
| `public/index.php` | `__DIR__ . '/../System/Core/NSY_Helpers_Global.php'` | `System/` |
| `System/Core/NSY_Helpers_Global.php` | `__DIR__ . '/../../env.php'` | `env.php`, sibling of `System/` |
| `System/Core/NSY_SystemLoader.php` | `__DIR__ . '/../../' . config_app('sys_dir')` | the `System/` folder |
| `System/Core/NSY_RouteLoader.php` | `.../../../{sys_dir}/Routes` | `System/Routes/` |
| `System/Libraries/Log/LogManager.php` | `dirname(__DIR__, 3) . '/' . LOG_DIR` | project root (parent of `System/`) |

So moving `System/` out of the web root changes **nothing** in
`public/index.php`. Only the URL-related env keys (`APP_DIR`, `PUBLIC_DIR`) and
`system.js` change, because the domain now points directly at `public_html`.

---

## Target directory layout

```text
/home/USERNAME/
├── public_html/                  # DocumentRoot
│   ├── index.php                 # copied from public/index.php
│   ├── .htaccess                 # copied from docs/apache/for_public/.htaccess
│   ├── assets/
│   │   ├── css/
│   │   ├── images/
│   │   └── js/
│   ├── 403.html
│   ├── 404.html
│   ├── 50x.html
│   ├── humans.txt
│   └── robots.txt
│
├── System/                       # OUTSIDE public_html — not web-reachable
│   ├── Apps/
│   │   └── Templates/razr_cache/   # writable (runtime)
│   ├── Config/
│   ├── Core/
│   ├── Libraries/
│   ├── Migrations/
│   ├── Routes/
│   ├── Storage/logs/               # writable (runtime)
│   └── Vendor/
│
└── env.php                       # OUTSIDE public_html (sibling of System/)
```

Only `System/` and `env.php` are uploaded to `/home/USERNAME/`. The rest of the
repository (`composer.json`, `docs/`, `.cli/`, `public/` as a folder, …) is **not
needed at runtime** and can stay in your repo/CI.

---

## Deploy steps

1. Upload the whole `System/` tree → `/home/USERNAME/System/`.
2. Upload the **contents** of `public/` → `/home/USERNAME/public_html/`
   (`index.php`, `assets/`, `403.html`, `404.html`, `50x.html`, `robots.txt`,
   `humans.txt`).
3. Upload `env.php` → `/home/USERNAME/env.php`.
4. Copy `.htaccess`:
   - `docs/apache/for_public/.htaccess` → `/home/USERNAME/public_html/.htaccess`
   - **Do not** copy `docs/apache/for_root/.htaccess` — that one is for the
     “whole project as document root” layout, not for `System/` outside `public_html`.
5. Edit `/home/USERNAME/env.php` → see [env.php](#envphp).
6. Edit `/home/USERNAME/public_html/assets/js/config/system.js` → see [system.js](#systemjs).
7. Set [filesystem permissions](#filesystem-permissions).
8. Run the [verification checklist](#verification-checklist).

Leave `public/index.php` and `System/Config/App.php` untouched.

---

## `env.php`

```php
'APP_ENV'    => 'production',
'APP_DIR'    => '',        // domain root == public_html → no sub-folder
'PUBLIC_DIR' => '',        // public_html IS the document root → no sub-folder

'LOG_ENABLED' => 'true',
'LOG_DIR'     => 'System/Storage/logs',   // relative to project root (parent of System/)

// fill in your production database, site meta, etc.
// 'connections' => [
//     'primary' => [
//         'DB_HOST' => 'localhost',
//         'DB_NAME' => 'your_db',
//         'DB_USER' => 'your_user',
//         'DB_PASS' => 'your_pass',
//     ],
// ],
```

| Key | Local | Hosting (`System/` outside `public_html`) | Notes |
| --- | --- | --- | --- |
| `APP_ENV` | `development` | `production` | hides errors, avoids leaking paths |
| `APP_DIR` | `nsy` | `''` | URL segment of the project; empty when the domain root is `public_html` |
| `PUBLIC_DIR` | `public` | `''` | URL segment of the web folder; empty for a document root |
| `LOG_DIR` | `System/Storage/logs` | `System/Storage/logs` | resolved relative to project root, so it keeps working |
| `CSRF_TOKEN` | `false` | `true` (recommended) | enable CSRF protection in production |

**Why both must be empty.** NSY builds URLs as:

```text
{scheme}://{host} / {APP_DIR} / {PUBLIC_DIR} / assets / ...
```

On hosting the real URL is `https://example.com/assets/...` (no `/nsy`, no
`/public`), so both segments must be empty.

> ⚠️ **Do not** set `PUBLIC_DIR` to `public_html`. It is also used to build asset
> URLs, which would then become `https://example.com/public_html/assets/...` and
> 404.

---

## `system.js`

`public_html/assets/js/config/system.js`:

```js
var dirname = "";   // was "nsy"
```

---

## Apache: `mod_rewrite`, `.htaccess` and `AllowOverride`

The file `docs/apache/for_public/.htaccess` is written for a **document root**
and already uses `RewriteBase /`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?/$1 [QSA,L]
</IfModule>
```

Because `REQUEST_URI` is preserved, routes registered as `/...`
(`System/Routes/General.php`) match correctly with `APP_DIR = ''`.

### Requirements on the hosting

- **`mod_rewrite` must be enabled** (standard on cPanel / DirectAdmin / Plesk).
- **`.htaccess` overrides must be allowed**, i.e. `AllowOverride All` — or at
  minimum `AllowOverride FileInfo Options` — for the `public_html` directory.

If the host uses nginx instead of Apache (no `.htaccess` support), adapt
`docs/nginx/sites-enabled/default` and ask the host to set the document root to
`public_html`.

### Forcing HTTPS

The HTTPS redirect block is present but commented out. Uncomment it in
`public_html/.htaccess` (around lines 356–360):

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} !=on
    RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [R=301,L]
</IfModule>
```

### How to confirm `AllowOverride` is active

Request an extension-less route, e.g. `https://example.com/docs/overview`:

- NSY page → overrides work.
- Apache’s own 404 (not NSY’s “Page Not Found”) → overrides are off; ask the host
  to enable `AllowOverride All` and `mod_rewrite`.

---

## Filesystem permissions

NSY only needs write access to **two** places, both inside `System/`:

| Path | Purpose |
| --- | --- |
| `System/Storage/logs/` | log files when `LOG_ENABLED=true` |
| `System/Apps/Templates/razr_cache/` | compiled Razr templates |

```bash
cd /home/USERNAME

# make sure the runtime dirs exist
mkdir -p System/Storage/logs System/Apps/Templates/razr_cache

# baseline: directories 755, files 644 (owner = hosting account user)
find System -type d -exec chmod 755 {} \;
find System -type f -exec chmod 644 {} \;

# grant write access to the two runtime directories
chmod -R 775 System/Storage/logs
chmod -R 775 System/Apps/Templates/razr_cache
```

Notes:

- On most shared hosts PHP runs **as your account user**, so `775` (or even `755`
  with owner write) is enough. Avoid `777` on a public server.
- `System/Storage/logs/.htaccess` ships a deny rule. Since logs now live outside
  the web root it is redundant, but harmless — keep it for defense in depth.
- The route cache uses the OS temp dir (`sys_get_temp_dir()/nsy_routes`), **not**
  the project folder, so no extra permission is required for it.
- If file upload / writable paths are added later, they live under
  `public_html/` and must be writable too.

---

## Verification checklist

1. `https://example.com/` → welcome page (not 404, not a directory listing).
2. Assets load — view-source shows `https://example.com/assets/css/...` and
   `https://example.com/assets/js/...`.
3. `https://example.com/docs/overview` → docs page (proves rewrite + empty
   `APP_DIR`).
4. `https://example.com/System/Config/App.php` → 404/403.
5. `https://example.com/env.php` → 404 (`env.php` is outside the web root).
6. HTTPS redirect works (if enabled).
7. Logs are written: `tail -f /home/USERNAME/System/Storage/logs/nsy-$(date +%F).log`.
8. `System/Apps/Templates/razr_cache/` fills after the first page render.

---

## Troubleshooting

| Symptom | Likely cause | Fix |
| --- | --- | --- |
| Every route 404s, but static files load | `.htaccess` not applied / `mod_rewrite` off | enable `AllowOverride All` + `mod_rewrite` |
| Every route 404s and assets 404 too | `APP_DIR` / `PUBLIC_DIR` still `nsy` / `public` | set both to `''` |
| `env file not found, please check in root folder.` | `env.php` not next to the `System/` parent | place at `/home/USERNAME/env.php` |
| Autoload / “class not found” error | `System/Vendor/` not uploaded, or `System/` not the sibling of the web folder | upload the full `System/` including `Vendor/` |
| Assets point to `/public/...` or `/public_html/...` | `PUBLIC_DIR` not empty | set `PUBLIC_DIR = ''` |
| Blank page, no errors | `APP_ENV=production` hides messages | check `System/Storage/logs/`, or temporarily set `development` |
| “Permission denied” writing logs / templates | runtime dirs not writable | `chmod -R 775` the two dirs above |

---

## Do NOT change

- `public/index.php` — `ENV_FILE` definition and the relative `require` paths.
- `System/Config/App.php` → `'sys_dir' => 'System'` stays, **unless** you actually
  rename the folder (see below).
- Namespaces `System\...` in every class — the folder name is not the namespace.

---

## Optional hardening: renaming `System/`

Renaming the folder is **not** a single move; the following must stay in sync:

1. Rename the folder.
2. `System/Config/App.php` → `'sys_dir' => '<NewName>'`.
3. `public/index.php` → the two hard-coded paths
   (`../System/Vendor/autoload.php`, `../System/Core/NSY_Helpers_Global.php`).
4. `composer.json` → PSR-4 `"System\\": "System/"` becomes `"System\\": "<NewName>/"`
   (and `vendor-dir` if you moved `Vendor/`).
5. `composer dump-autoload -o` (or `nsy dump:autoload`).
6. Optionally rename `env.php` and update `define('ENV_FILE', '...')` in
   `public/index.php`.

---

## Local vs hosting reference

| | Local (`/var/www/html/nsy`) | Hosting |
| --- | --- | --- |
| Web folder | `nsy/public` | `/home/USERNAME/public_html` |
| System | `nsy/System` | `/home/USERNAME/System` |
| `env.php` | `nsy/env.php` | `/home/USERNAME/env.php` |
| `APP_DIR` | `nsy` | `''` |
| `PUBLIC_DIR` | `public` | `''` |
| `sys_dir` | `System` | `System` |
| `system.js dirname` | `nsy` | `''` |
| `.htaccess` | `docs/apache/for_public/` → `public/` | `docs/apache/for_public/` → `public_html/` |

---

## Known trade-off: asset cache-busting

`public_path()` in `System/Core/NSY_Helpers_Global.php` guesses the web folder
from `public_dir` (`System/Core/../../{public_dir}`). With `PUBLIC_DIR = ''` it
can no longer find `assets/...`, so `css_url()`, `js_url()` and `img_url()` skip
the `?v=filemtime` cache-busting suffix. Pages still work; only cache-busting is
lost.

If you want it back without touching core code, symlink the asset folders so they
sit next to `System/`:

```bash
cd /home/USERNAME
ln -s public_html/assets assets
ln -s public_html/css    css
ln -s public_html/js     js
ln -s public_html/images images
```

Alternatively, rely on the caching/expiry headers already configured in
`public_html/.htaccess`. Do **not** “fix” this by setting
`PUBLIC_DIR = 'public_html'` — that breaks asset URLs.
