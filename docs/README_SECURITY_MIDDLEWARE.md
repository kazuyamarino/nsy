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

Use `Route` as the single entry point:

```php
echo Route::csrfField('csrf_token');
echo Route::csrfMeta('csrf_token');
$token = Route::csrf('csrf_token');

Route::post('/submit', function () {
    if (!Route::validateCsrf($_POST['csrf_token'] ?? null, 'csrf_token')) {
        http_response_code(403);
        return;
    }
});
```

See `docs/README_NSY_ROUTER.md#csrf-protection` for full tutorial.

## Input Sanitization

### Basic

```php
$cleanInput = SecurityMiddleware::sanitizeInput($userInput);
$cleanData = SecurityMiddleware::sanitizeForm($_POST);
```

### Advanced

```php
$cleanData = SecurityMiddleware::validateAndSanitize($_POST, [
    'trim' => true,
    'strip_slashes' => true,
    'html_escape' => true,
    'xss_clean' => true,
    'max_length' => 255,
    'allowed_tags' => '<p><br><strong><em>'
]);
```

| Option | Default | Description |
|--------|---------|-------------|
| `trim` | `true` | Remove whitespace |
| `strip_slashes` | `true` | Remove backslashes |
| `html_escape` | `true` | Escape HTML |
| `xss_clean` | `false` | Apply AntiXSS |
| `max_length` | `null` | Max length |
| `allowed_tags` | `null` | Allowed HTML tags |

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
    'csrf_protection' => true,
    'rate_limit' => 60,
    'rate_window' => 60,
    'validate_input' => true,
    'block_suspicious_patterns' => true
]);
```

## API Reference

| Method | Signature | Description |
|---|---|---|
| `sanitizeInput` | `sanitizeInput(mixed $data=''):string` | Sanitize single string |
| `sanitizeForm` | `sanitizeForm(mixed $form=''):mixed` | Recursively sanitize |
| `cleanXSS` | `cleanXSS(mixed $data):mixed` | XSS clean |
| `validateAndSanitize` | `validateAndSanitize(mixed $data, array $options=[]):mixed` | Core sanitization |

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

