# NSY SecurityMiddleware Documentation

> File: `System/Middlewares/SecurityMiddleware.php`

## Table of Contents

1. [Quick Start](#quick-start)
2. [CSRF Protection](#csrf-protection)
3. [Input Sanitization](#input-sanitization)
4. [XSS Protection](#xss-protection)
5. [Advanced Validation](#advanced-validation)
6. [Configuration](#configuration)
7. [API Reference](#api-reference)

---

## Quick Start

```php
use System\Middlewares\SecurityMiddleware;

$clean = SecurityMiddleware::sanitizeForm($_POST);
$safe = SecurityMiddleware::cleanXSS($userInput);
```

## CSRF Protection

Prefer the `Route` facade as the single entry point (see
[`README_NSY_ROUTER.md#csrf-protection`](README_NSY_ROUTER.md#csrf-protection)
for the full tutorial):

```php
echo Route::csrfField('csrf_token');
echo Route::csrfMeta('csrf_token');
$token = Route::csrf('csrf_token');

Route::post('/submit', function () {
    if (!Route::validateCsrf($_POST['token'] ?? null, 'csrf_token')) {
        http_response_code(403);
        return;
    }
});
```

The same calls are available directly on `SecurityMiddleware` (used internally and
kept for backwards compatibility):

```php
use System\Middlewares\SecurityMiddleware;

// Single form — csrfField derives the input name from the key (csrf_token → name="token")
echo SecurityMiddleware::csrfField('csrf_token');                 // <input name="token" …>
SecurityMiddleware::validateCSRFToken($_POST['token'] ?? null, 'csrf_token'); // bool

// Multi-key form — the key is the session key and the derived field name
SecurityMiddleware::generateCSRFTokenForKey('login');             // stored as $_SESSION['login']
SecurityMiddleware::validateAdvancedCSRF('login', $_POST, false, 600, false, false); // reads $_POST['login']
```

`$expiration` (seconds) is checked on validation, not at generation. Passing
`$enableOriginCheck = true` embeds an IP + User-Agent hash, so a token copied to
another client is rejected.

A generated token is **reused** on subsequent calls for the same key until it is
consumed by a successful validation or expires, so several `csrfField()` /
`csrfMeta()` calls on one page share a single token. Validation is single-use:
once it succeeds the token is cleared and the next page issues a new one.

## Input Sanitization

### Basic

```php
$cleanInput = SecurityMiddleware::sanitizeInput($userInput);   // trim + strip control chars
$cleanData = SecurityMiddleware::sanitizeForm($_POST);
```

> `sanitizeInput()` (and `sanitizeForm()`) **normalise** input — trim and drop
> control characters — but do **not** HTML-escape or strip slashes. Escaping is
> an output concern: use `htmlspecialchars()`/`e()` when you print. Escaping on
> input double-encodes values and corrupts legitimate backslashes.

### Advanced

```php
$cleanData = SecurityMiddleware::validateAndSanitize($_POST, [
    'trim' => true,
    'strip_control_chars' => false,
    'strip_slashes' => true,
    'html_escape' => true,
    'xss_clean' => true,
    'max_length' => 255,
    'allowed_tags' => '<p><br><strong><em>'
]);
```

| Option | Default | Description |
|--------|---------|-------------|
| `trim` | `true` | Remove surrounding whitespace |
| `strip_control_chars` | `false` | Remove NUL/other control bytes |
| `strip_slashes` | `true` | Remove backslashes (legacy; opt out when storing paths/code) |
| `html_escape` | `true` | Escape HTML (output concern — set `false` if you escape when printing) |
| `xss_clean` | `false` | Apply AntiXSS |
| `max_length` | `null` | Max length |
| `allowed_tags` | `null` | Allowed HTML tags — kept, but their scriptable attributes are stripped |

> `allowed_tags` uses `strip_tags()` for the whitelist **and** then runs the
> result through AntiXSS, so allowed tags keep no `on*`/`javascript:` attributes.
> If AntiXSS is unavailable the allowed-tag output is fully escaped instead.

### Recursive Example

```php
$data = [
    'user' => [
        'name' => '  John Doe  ',
        'profile' => ['bio' => '<script>alert("xss")</script>Safe']
    ]
];
$cleanData = SecurityMiddleware::sanitizeForm($data);
```

## XSS Protection

```php
$safe = SecurityMiddleware::cleanXSS('<script>alert("XSS")</script>Hello');
// "Hello"

$inputs = [
    'title' => '<script>alert(1)</script>Safe Title',
    'tags' => ['<img onerror="alert(1)">', 'safe-tag']
];
$clean = SecurityMiddleware::cleanXSS($inputs);
```

Uses `AntiXSS` if available, otherwise `htmlspecialchars`.

## Advanced Validation

```php
$userData = SecurityMiddleware::validateAndSanitize($_POST, [
    'trim' => true,
    'html_escape' => true,
    'xss_clean' => true,
    'max_length' => 1000,
]);

function validateUserRegistration($data) {
    $data = SecurityMiddleware::sanitizeForm($data);
    $data = SecurityMiddleware::cleanXSS($data);
    return SecurityMiddleware::validateAndSanitize($data, [
        'xss_clean' => true,
        'max_length' => 255
    ]);
}
```

## Configuration

```php
use System\Middlewares\SecurityMiddleware;

$security = new SecurityMiddleware([
    'rate_limit' => 60,   // max hits per window
    'rate_window' => 60,  // window length in seconds
]);

if (!$security->rateLimit('login')) {
    http_response_code(429);
    return;
}
```

## API Reference

### Constructor

| Method | Signature | Description |
|---|---|---|
| `__construct` | `__construct(array $config=[]):void` | Merge config: `rate_limit`, `rate_window` (the only keys enforced here) |

### Sanitization

| Method | Signature | Description |
|---|---|---|
| `sanitizeInput` | `sanitizeInput(mixed $data=''):string` | Sanitize a single scalar (non-scalars return `''`) |
| `sanitizeForm` | `sanitizeForm(mixed $form=''):mixed` | Recursively sanitize arrays/objects |
| `cleanXSS` | `cleanXSS(mixed $data):mixed` | XSS clean |
| `validateAndSanitize` | `validateAndSanitize(mixed $data, array $options=[]):mixed` | Core sanitization (arrays/objects recursed) |

> For whole-form input use `sanitizeForm()` / `validateAndSanitize()` — both walk arrays **and** objects. `sanitizeInput()` is for a single scalar value; arrays/objects given to it return `''` rather than raising a conversion error. `sanitizeInput()` normalises only (trim + strip control chars); it does **not** HTML-escape.

### CSRF

| Method | Signature | Description |
|---|---|---|
| `generateCSRFToken` | `generateCSRFToken(string $key='csrf_token', ?int $expiration=null, bool $enableOriginCheck=false):string` | Generate a token and store it in `$_SESSION[$key]` |
| `generateCSRFTokenForKey` | `generateCSRFTokenForKey(string $key, bool $enableOriginCheck=false):string` | Generate a token stored under `$key` |
| `csrfField` | `csrfField(string $key='csrf_token', ?int $expiration=null, bool $enableOriginCheck=false):string` | Hidden `<input>` field (name = `csrfFieldName($key)`) |
| `csrfMeta` | `csrfMeta(string $key='csrf_token', ?int $expiration=null, bool $enableOriginCheck=false):string` | `<meta name="csrf-token">` tag |
| `csrfFieldName` | `csrfFieldName(string $key):string` | Derive the field name (`csrf_token` → `token`) |
| `validateCSRFToken` | `validateCSRFToken(?string $token, string $key='csrf_token', ?int $expiration=null, bool $originCheck=false):bool` | Single-use validation; the key is the session key |
| `checkCSRFToken` | `checkCSRFToken(string $key, string $token, bool $throwException=false, ?int $timeSpan=null, bool $multiple=false):bool` | Core check; consumes the token (clears it) only on success unless `$multiple` |
| `validateAdvancedCSRF` | `validateAdvancedCSRF(string $key, array $origin, bool $throwException=false, ?int $timeSpan=null, bool $multiple=false, bool $enableOriginCheck=false):bool` | Validate the derived field `csrfFieldName($key)` (falling back to `$key`) from `$origin` |
| `createCSRFProtection` | `createCSRFProtection(bool $enableOriginCheck=false):object` | Factory with `generate()` / `check()` (backwards compatibility) |

> `$expiration` / `$timeSpan` are enforced on **validation**, not at generation. `$enableOriginCheck` binds the token to the client IP + User-Agent, so a token copied to another client is rejected.

### Rate Limiting

| Method | Signature | Description |
|---|---|---|
| `rateLimit` | `rateLimit(string $bucket='default', ?int $maxAttempts=null, ?int $windowSeconds=null):bool` | Instance check using config (`true` = allowed) |
| `hit` | `hit(string $key, int $maxAttempts, int $windowSeconds):bool` | Static fixed-window counter (`true` = allowed) |

Fixed-window, file-backed per client IP + bucket under `System/Storage/ratelimit`.
It **fails open** (returns `true`) if the counter cannot be stored, so a storage
problem never locks users out. Use one bucket per sensitive action, e.g.
`$security->rateLimit('login')`, `$security->rateLimit('password-reset')`.

## Examples

### Form Handling

```php
$clean = SecurityMiddleware::validateAndSanitize($_POST, [
    'trim' => true,
    'html_escape' => true,
    'xss_clean' => true,
    'max_length' => 255
]);
updateUserProfile($clean);
```

### API Input

```php
$input = json_decode(file_get_contents('php://input'), true);
$cleanInput = SecurityMiddleware::cleanXSS($input);
processApiRequest($cleanInput);
```

