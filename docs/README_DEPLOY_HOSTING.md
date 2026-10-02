# Deploying NSY to Shared Hosting — `System/` outside `public_html`

> **Scope:** shared / cPanel-style hosting where the document root is
> `public_html`, and the framework (`System/`, `env.php`) must be kept **outside**
> the web root.
>
> Locally NSY runs as `project_root/{public, System, env.php}`. On hosting the
> same layout applies — only the web folder is renamed to `public_html` and moved
> so that `System/` is no longer web-reachable.

## Table of Contents

1. [Why this works (no `index.php` edit needed)](#why-this-works-no-indexphp-edit-needed)
2. [Target directory layout](#target-directory-layout)
3. [Deploy steps](#deploy-steps)
4. [`env.php`](#envphp)
5. [`system.js`](#systemjs)
6. [Apache: `mod_rewrite`, `.htaccess` and `AllowOverride`](#apache-mod_rewrite-htaccess-and-allowoverride)
7. [nginx: `server` block and `try_files`](#nginx-server-block-and-try_files)
8. [Filesystem permissions](#filesystem-permissions)
9. [Verification checklist](#verification-checklist)
10. [Troubleshooting](#troubleshooting)
11. [Do NOT change](#do-not-change)
12. [Optional hardening: renaming `System/`](#optional-hardening-renaming-system)
13. [Local vs hosting reference](#local-vs-hosting-reference)
14. [Known trade-off: asset cache-busting](#known-trade-off-asset-cache-busting)

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
│   ├── .htaccess                 # Apache only — from docs/apache/for_public/
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
├── docs/                         # OUTSIDE public_html — REQUIRED (see below)
│   └── *.md                      # read at runtime by the Docs Viewer
│
└── env.php                       # OUTSIDE public_html (sibling of System/)
```

Three things are uploaded to `/home/USERNAME/`: `System/`, `docs/` and `env.php`.

> **`docs/` is required at runtime.** The in-app documentation viewer reads
> `<project root>/docs/*.md` on every request (see `System\Libraries\Docs`).
> Without it the sidebar renders empty and **every** `/docs/…` URL returns 404.
> It sits next to `System/`, *not* inside `public_html` — it is read from disk by
> PHP, never served as a static file, so it must stay outside the web root.

The rest of the repository (`composer.json`, `.cli/`, `public/` as a folder, …) is
**not needed at runtime** and can stay in your repo/CI.

---

## Deploy steps

> **Dependencies first.** `System/Vendor/` is **not** committed. Build it locally with
> `composer install --no-dev --optimize-autoloader`, then upload it as part of
> `System/` — or run the same command on the server if Composer is available.

1. Upload the whole `System/` tree → `/home/USERNAME/System/`.
2. Upload the **contents** of `public/` → `/home/USERNAME/public_html/`
   (`index.php`, `assets/`, `403.html`, `404.html`, `50x.html`, `robots.txt`,
   `humans.txt`).
3. Upload `docs/` → `/home/USERNAME/docs/` — a **sibling** of `System/`, not a
   child of `public_html/`. Skip this only if you intend the `/docs/…` routes to
   return 404.
4. Upload `env.php` → `/home/USERNAME/env.php`.
5. Configure web-server routing:
   - **Apache:** copy `docs/apache/for_public/.htaccess` →
     `/home/USERNAME/public_html/.htaccess`. **Do not** copy
     `docs/apache/for_root/.htaccess` — that one is for the “whole project as
     document root” layout.
   - **nginx:** `.htaccess` is ignored — configure the `server` block instead, see
     [nginx: `server` block and `try_files`](#nginx-server-block-and-try_files).
6. Edit `/home/USERNAME/env.php` → see [env.php](#envphp).
7. Edit `/home/USERNAME/public_html/assets/js/config/system.js` → see [system.js](#systemjs).
8. Set [filesystem permissions](#filesystem-permissions).
9. Run the [verification checklist](#verification-checklist).

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

## nginx: `server` block and `try_files`

nginx **does not read `.htaccess`** — the `docs/apache/for_public/.htaccess` file
has no effect there. Everything is configured in the `server` block. The files
under `docs/nginx/` target the **local** layout (project in a sub-folder,
`APP_DIR=nsy`); for hosting, adapt them to the layout above (`System/` outside
`public_html`, `APP_DIR=''`).

`try_files` is the equivalent of the Apache `RewriteRule`: it sends
extension-less requests to `index.php`, while nginx keeps the original
`REQUEST_URI`, so routes registered as `/...` still match with `APP_DIR = ''`.

```nginx
# /etc/nginx/sites-available/example.com   (then symlink into sites-enabled/)
server {
    listen 80;
    listen [::]:80;
    server_name example.com www.example.com;

    # Force HTTPS (Apache “Forcing https://” block equivalent)
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl;
    listen [::]:443 ssl;
    http2 on;                          # nginx >= 1.25.1; older: listen 443 ssl http2;
    server_name example.com www.example.com;

    root /home/USERNAME/public_html;   # document root = the old public/
    index index.php;

    # TLS — use your host's / certbot's paths
    ssl_certificate     /etc/letsencrypt/live/example.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/example.com/privkey.pem;

    autoindex off;
    client_max_body_size 16m;          # raise if you accept uploads

    # Front controller — Apache RewriteRule equivalent
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM — adjust the socket to your PHP version
    location ~ \.php$ {
        try_files $uri =404;
        include fastcgi_params;        # some distros ship `fastcgi.conf`
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param HTTPS on;        # so base_url() detects the https scheme
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
    }

    # Block dotfiles (.env, .git, …)
    location ~ /\. { deny all; }

    # Optional: long cache for static assets
    location /assets/ {
        try_files $uri =404;
        expires 30d;
        access_log off;
    }

    # Error pages shipped in public_html/
    error_page 404 /404.html;
    error_page 403 /403.html;
    error_page 500 502 503 504 /50x.html;
}
```

### Why there is no `deny` block for `System/`

`System/` and `env.php` live **above** `root` (`/home/USERNAME/`), so nginx cannot
serve them — there is nothing to deny. The
`location ^~ /nsy/System/ { deny all; }` block in
`docs/nginx/sites-enabled/default` is only needed for the **local** layout, where
the project sits inside a served folder.

> If you cannot move `System/` out of the document root, add a safety net:
> ```nginx
> location ^~ /System/ { deny all; return 404; }
> ```

### Apply and verify

```bash
sudo nginx -t && sudo systemctl reload nginx
```

- `https://example.com/` and `https://example.com/docs/overview` → rendered by NSY
  (proves `try_files` works).
- `https://example.com/assets/css/...` → served as a static file.
- `https://example.com/env.php` → 404 (outside `root`).
- HTTP request → `301` redirect to HTTPS.

> **Managed nginx hosting:** if you cannot edit the `server` block, ask the host
> to set the document root to `public_html` and add the front-controller
> `try_files $uri $uri/ /index.php?$query_string;` — that single line is what
> makes pretty routes work.

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
3. `https://example.com/docs/overview` → docs page (proves the Apache rewrite /
   nginx `try_files` + empty `APP_DIR`).
4. `https://example.com/docs/router` → a second doc page. This one specifically
   proves `docs/` was uploaded: a routing that works but an empty sidebar means
   the folder is missing on the server.
5. `https://example.com/System/Config/App.php` → 404/403.
6. `https://example.com/docs/` → the documentation index lists all guides (not
   an empty sidebar, not 404).
7. `https://example.com/env.php` → 404 (`env.php` is outside the web root).
8. HTTPS redirect works (if enabled).
9. Logs are written: `tail -f /home/USERNAME/System/Storage/logs/nsy-$(date +%F).log`.
10. `System/Apps/Templates/razr_cache/` fills after the first page render.

---

## Troubleshooting

| Symptom | Likely cause | Fix |
| --- | --- | --- |
| Every route 404s, but static files load | `.htaccess` not applied / `mod_rewrite` off | enable `AllowOverride All` + `mod_rewrite` |
| Every route 404s and assets 404 too | `APP_DIR` / `PUBLIC_DIR` still `nsy` / `public` | set both to `''` |
| `env file not found, please check in root folder.` | `env.php` not next to the `System/` parent | place at `/home/USERNAME/env.php` |
| Autoload / “class not found” error | `System/Vendor/` missing (it is not shipped), or `System/` not the sibling of the web folder | run `composer install --no-dev` locally and upload `System/Vendor/` (or run it on the server) |
| Assets point to `/public/...` or `/public_html/...` | `PUBLIC_DIR` not empty | set `PUBLIC_DIR = ''` |
| Blank page, no errors | `APP_ENV=production` hides messages | check `System/Storage/logs/`, or temporarily set `development` |
| “Permission denied” writing logs / templates | runtime dirs not writable | `chmod -R 775` the two dirs above |
| `.htaccess` changes have no effect | server is nginx (ignores `.htaccess`) | configure the nginx `server` block instead |
| Every route 404s on nginx, static files load | no front-controller `try_files` | add `try_files $uri $uri/ /index.php?$query_string;` |
| Every `/docs/…` URL 404s, but the rest of the site works | `docs/` was not uploaded | copy `docs/` next to `System/` (`/home/USERNAME/docs/`) |
| `/docs/overview` renders, but the sidebar is empty | `docs/` missing or unreadable by the web user | same as above, then `chmod -R 755 /home/USERNAME/docs` |
| `502 Bad Gateway` on nginx | PHP-FPM socket/TCP wrong or FPM not running | fix `fastcgi_pass` / start php-fpm |

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
| Routing (Apache) | `docs/apache/for_public/` → `public/` | `docs/apache/for_public/` → `public_html/` |
| Routing (nginx) | `docs/nginx/sites-enabled/default` (sub-folder layout) | `server` block with `root public_html` + `try_files` |

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
