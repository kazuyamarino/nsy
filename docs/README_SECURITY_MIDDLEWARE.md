# NSY SecurityMiddleware Documentation

The NSY SecurityMiddleware provides a comprehensive security suite for the NSY Framework, offering CSRF protection, input sanitization, XSS prevention, rate limiting, and advanced validation capabilities. This middleware serves as the centralized security layer for all framework operations.

## Table of Contents

1. [Overview](#overview)
2. [Installation & Setup](#installation--setup)
3. [CSRF Protection](#csrf-protection)
4. [Input Sanitization](#input-sanitization)
5. [XSS Protection](#xss-protection)
6. [Rate Limiting](#rate-limiting)
7. [Advanced Validation](#advanced-validation)
8. [Configuration](#configuration)
9. [API Reference](#api-reference)
10. [Best Practices](#best-practices)
11. [Examples](#examples)
12. [Troubleshooting](#troubleshooting)

## Overview

SecurityMiddleware is the consolidated security solution for NSY Framework, providing:

- **CSRF Protection**: Token-based request validation with expiration and origin checking
- **Input Sanitization**: Multi-level data cleaning and validation
- **XSS Protection**: Cross-site scripting prevention with AntiXSS integration
- **Rate Limiting**: Request throttling by IP address
- **Advanced Validation**: Configurable validation with multiple security layers

### Key Features

✅ **Token Expiration**: CSRF tokens with configurable expiration times  
✅ **Origin Validation**: IP + User Agent binding for enhanced security  
✅ **Multiple Tokens**: Support for complex multi-step forms  
✅ **Recursive Sanitization**: Deep cleaning of nested data structures  
✅ **Fallback Protection**: Graceful degradation when dependencies unavailable  
✅ **Performance Optimized**: Efficient validation with minimal overhead  

## Installation & Setup

### Basic Configuration

```php
<?php
use System\Middlewares\SecurityMiddleware;

// Create security middleware with custom config
$security = new SecurityMiddleware([
    'csrf_protection' => true,
    'rate_limit' => 60, // requests per minute
    'rate_window' => 60, // seconds
    'validate_input' => true,
    'block_suspicious_patterns' => true
]);
```

### Security Levels

```php
// Predefined security levels
$basicSecurity = SecurityMiddleware::createSecurityLevel('basic');
$standardSecurity = SecurityMiddleware::createSecurityLevel('standard');
$strictSecurity = SecurityMiddleware::createSecurityLevel('strict');
```

| Level | Rate Limit | CSRF | Input Validation | Pattern Blocking |
|-------|------------|------|------------------|------------------|
| `basic` | 200/min | ❌ | ✅ | ❌ |
| `standard` | 100/min | ✅ | ✅ | ✅ |
| `strict` | 30/min | ✅ | ✅ | ✅ |

## CSRF Protection

### Basic CSRF Usage

```php
// Generate CSRF token
$token = SecurityMiddleware::generateCSRFToken();

// Generate CSRF token with custom key
$token = SecurityMiddleware::generateCSRFToken('payment_token');

// Generate CSRF token with expiration (30 minutes)
$token = SecurityMiddleware::generateCSRFToken('form_token', 1800);

// Generate CSRF token with origin validation
$token = SecurityMiddleware::generateCSRFToken('secure_token', null, true);
```

### Advanced CSRF Features

```php
// Token with expiration and origin validation
$secureToken = SecurityMiddleware::generateCSRFToken(
    'critical_action',  // Token key
    3600,              // 1 hour expiration
    true               // Enable origin validation (IP + User Agent)
);

// Validate CSRF token
$isValid = SecurityMiddleware::validateCSRFToken(
    $_POST['csrf_token'],
    'critical_action',
    3600,
    true
);

if (!$isValid) {
    http_response_code(403);
    exit('CSRF validation failed');
}
```

### HTML Generation

```php
// Generate HTML input field
$field = SecurityMiddleware::csrfField();
// Output: <input type="hidden" name="_token" value="abc123...">

// Generate HTML input field with custom key
$field = SecurityMiddleware::csrfField('payment_token', 1800);
// Output: <input type="hidden" name="payment_token" value="def456...">

// Generate meta tag for AJAX
$meta = SecurityMiddleware::csrfMeta();
// Output: <meta name="csrf-token" content="ghi789...">

// Generate meta tag with origin validation
$meta = SecurityMiddleware::csrfMeta('ajax_token', null, true);
```

### Multiple Tokens

```php
// For complex multi-step forms
$tokens = [];
$tokens['step1'] = SecurityMiddleware::generateCSRFToken('wizard_step1');
$tokens['step2'] = SecurityMiddleware::generateCSRFToken('wizard_step2');
$tokens['final'] = SecurityMiddleware::generateCSRFToken('wizard_final');

// Validate each step
foreach ($tokens as $step => $expectedToken) {
    if (!SecurityMiddleware::validateCSRFToken($_POST[$step], $step)) {
        throw new Exception("Step {$step} validation failed");
    }
}
```

## Input Sanitization

### Basic Sanitization

```php
// Sanitize single input
$cleanInput = SecurityMiddleware::sanitizeInput($userInput);
// Applies: trim(), stripslashes(), htmlspecialchars()

// Sanitize form data (arrays/objects)
$cleanData = SecurityMiddleware::sanitizeForm($_POST);
// Recursively sanitizes all values
```

### Advanced Sanitization

```php
// Comprehensive validation with options
$cleanData = SecurityMiddleware::validateAndSanitize($_POST, [
    'trim' => true,
    'strip_slashes' => true,
    'html_escape' => true,
    'xss_clean' => true,
    'max_length' => 255,
    'allowed_tags' => '<p><br><strong><em>'
]);
```

### Sanitization Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `trim` | bool | `true` | Remove whitespace from start/end |
| `strip_slashes` | bool | `true` | Remove backslashes from strings |
| `html_escape` | bool | `true` | Escape HTML special characters |
| `xss_clean` | bool | `false` | Apply XSS cleaning (requires AntiXSS) |
| `max_length` | int | `null` | Maximum string length |
| `allowed_tags` | string | `null` | Allowed HTML tags |

### Recursive Sanitization

```php
$complexData = [
    'user' => [
        'name' => '  John Doe  ',
        'email' => 'john@example.com',
        'profile' => [
            'bio' => '<script>alert("xss")</script>Safe content',
            'social' => [
                'twitter' => '@johndoe',
                'website' => 'https://johndoe.com'
            ]
        ]
    ]
];

// Recursively sanitize all nested data
$cleanData = SecurityMiddleware::sanitizeForm($complexData);
```

## XSS Protection

### Basic XSS Cleaning

```php
// Clean single string
$maliciousInput = '<script>alert("XSS")</script>Hello World';
$safeOutput = SecurityMiddleware::cleanXSS($maliciousInput);
// Output: "Hello World"

// Clean array of strings
$inputs = [
    'title' => '<script>alert(1)</script>Safe Title',
    'content' => 'Normal content',
    'tags' => ['<img onerror="alert(1)">', 'safe-tag']
];
$cleanInputs = SecurityMiddleware::cleanXSS($inputs);
```

### XSS with Fallback

```php
// Uses AntiXSS library if available, falls back to htmlspecialchars
$safeContent = SecurityMiddleware::cleanXSS($userContent);

// Manual fallback check
if (!class_exists('voku\helper\AntiXSS')) {
    // Will use htmlspecialchars as fallback
    echo "Using fallback XSS protection";
}
```

## Rate Limiting

Rate limiting is applied automatically when SecurityMiddleware is used as middleware, but can also be checked manually:

```php
// Manual rate limit check
$security = new SecurityMiddleware([
    'rate_limit' => 30,    // 30 requests
    'rate_window' => 60    // per minute
]);

// This happens automatically in middleware
if (!$security->checkRateLimit()) {
    http_response_code(429);
    exit('Rate limit exceeded');
}
```

## Advanced Validation

### Comprehensive Validation

```php
// Validate with multiple security layers
$userData = SecurityMiddleware::validateAndSanitize($_POST, [
    'trim' => true,
    'strip_slashes' => true,
    'html_escape' => true,
    'xss_clean' => true,           // Enable XSS cleaning
    'max_length' => 1000,          // Limit string length
    'allowed_tags' => '<p><br>'    // Allow specific HTML tags
]);

// Validate sensitive data with strict settings
$paymentData = SecurityMiddleware::validateAndSanitize($_POST, [
    'trim' => true,
    'strip_slashes' => true,
    'html_escape' => true,
    'xss_clean' => true,
    'max_length' => 50
]);
```

### Custom Validation Pipeline

```php
// Multi-step validation
function validateUserRegistration($data) {
    // Step 1: Basic sanitization
    $data = SecurityMiddleware::sanitizeForm($data);
    
    // Step 2: XSS protection
    $data = SecurityMiddleware::cleanXSS($data);
    
    // Step 3: Advanced validation
    $data = SecurityMiddleware::validateAndSanitize($data, [
        'xss_clean' => true,
        'max_length' => 255
    ]);
    
    return $data;
}
```

## Configuration

### Security Levels Configuration

```php
// Basic configuration (200 req/min, minimal protection)
$basic = [
    'rate_limit' => 200,
    'csrf_protection' => false,
    'validate_input' => true,
    'block_suspicious_patterns' => false
];

// Standard configuration (100 req/min, CSRF enabled)
$standard = [
    'rate_limit' => 100,
    'csrf_protection' => true,
    'validate_input' => true,
    'block_suspicious_patterns' => true
];

// Strict configuration (30 req/min, all protections)
$strict = [
    'rate_limit' => 30,
    'csrf_protection' => true,
    'validate_input' => true,
    'block_suspicious_patterns' => true
];
```

### Custom Configuration

```php
$customSecurity = new SecurityMiddleware([
    'csrf_protection' => true,
    'rate_limit' => 150,           // Custom rate limit
    'rate_window' => 60,           // 1 minute window
    'validate_input' => true,
    'block_suspicious_patterns' => true,
    'custom_patterns' => [         // Additional suspicious patterns
        '/eval\s*\(/i',
        '/base64_decode/i'
    ]
]);
```

## API Reference

### CSRF Methods

#### `generateCSRFToken(string $key = '_csrf_token', ?int $expiration = null, bool $originCheck = false): string`

Generate a CSRF token with optional expiration and origin validation.

**Parameters:**
- `$key` (string): Token identifier key
- `$expiration` (int|null): Token expiration in seconds
- `$originCheck` (bool): Enable IP + User Agent validation

**Returns:** `string` - Base64 encoded CSRF token

---

#### `validateCSRFToken(?string $token, string $key = 'csrf_token', ?int $expiration = null, bool $originCheck = false): bool`

Validate a CSRF token against stored session token.

**Parameters:**
- `$token` (string|null): Token to validate
- `$key` (string): Token identifier key
- `$expiration` (int|null): Expected token expiration
- `$originCheck` (bool): Validate origin (IP + User Agent)

**Returns:** `bool` - True if token is valid

---

#### `csrfField(string $key = '_csrf_token', ?int $expiration = null, bool $originCheck = false): string`

Generate HTML input field with CSRF token.

**Returns:** `string` - HTML input element

---

#### `csrfMeta(string $key = '_csrf_token', ?int $expiration = null, bool $originCheck = false): string`

Generate HTML meta tag with CSRF token for AJAX requests.

**Returns:** `string` - HTML meta element

---

### Sanitization Methods

#### `sanitizeInput(string $data = ''): string`

Sanitize a single input string.

**Parameters:**
- `$data` (string): Input data to sanitize

**Returns:** `string` - Sanitized data

---

#### `sanitizeForm($form = ''): mixed`

Recursively sanitize form data (arrays, objects, strings).

**Parameters:**
- `$form` (mixed): Form data to sanitize

**Returns:** `mixed` - Sanitized form data

---

#### `cleanXSS($data): string|array`

Clean XSS from input data using AntiXSS library with fallback.

**Parameters:**
- `$data` (string|array): Data to clean from XSS

**Returns:** `string|array` - XSS-cleaned data

---

#### `validateAndSanitize($data, array $options = []): mixed`

Comprehensive input validation and sanitization with configurable options.

**Parameters:**
- `$data` (mixed): Data to validate and sanitize
- `$options` (array): Validation options

**Returns:** `mixed` - Validated and sanitized data

---

### Utility Methods

#### `checkCSRFToken(string $key, string $token, bool $throwException = false, ?int $timeSpan = null, bool $multiple = false): bool`

Low-level CSRF token validation with advanced options.

#### `generateCSRFTokenForKey(string $key, bool $enableOriginCheck = false): string`

Generate CSRF token for specific key with origin checking option.

#### `createCSRFProtection(bool $enableOriginCheck = false): object`

Create anonymous class instance for CSRF protection (legacy compatibility).

## Best Practices

### 1. CSRF Protection
```php
// ✅ Good: Use expiration for sensitive operations
$token = SecurityMiddleware::generateCSRFToken('payment', 1800, true);

// ✅ Good: Validate with same parameters
$isValid = SecurityMiddleware::validateCSRFToken($token, 'payment', 1800, true);

// ❌ Avoid: Long-lived tokens for sensitive operations
$token = SecurityMiddleware::generateCSRFToken('payment'); // No expiration
```

### 2. Input Sanitization
```php
// ✅ Good: Always sanitize user input
$cleanData = SecurityMiddleware::sanitizeForm($_POST);

// ✅ Good: Use appropriate validation for context
$htmlContent = SecurityMiddleware::validateAndSanitize($input, [
    'xss_clean' => true,
    'allowed_tags' => '<p><br><strong><em>'
]);

// ❌ Avoid: Direct usage of unsanitized input
echo $_POST['content']; // Dangerous
```

### 3. XSS Protection
```php
// ✅ Good: Clean before output
echo SecurityMiddleware::cleanXSS($userContent);

// ✅ Good: Clean arrays recursively
$cleanArray = SecurityMiddleware::cleanXSS($_POST);

// ❌ Avoid: Trusting user input
echo $_GET['search']; // XSS vulnerability
```

### 4. Security Layers
```php
// ✅ Good: Multiple security layers
function processUserData($data) {
    // Layer 1: CSRF validation
    if (!SecurityMiddleware::validateCSRFToken($_POST['token'])) {
        throw new SecurityException('CSRF validation failed');
    }
    
    // Layer 2: Input sanitization
    $data = SecurityMiddleware::sanitizeForm($data);
    
    // Layer 3: XSS protection
    $data = SecurityMiddleware::cleanXSS($data);
    
    // Layer 4: Advanced validation
    return SecurityMiddleware::validateAndSanitize($data, [
        'xss_clean' => true,
        'max_length' => 1000
    ]);
}
```

### 5. Performance Considerations
```php
// ✅ Good: Reuse tokens where appropriate
$token = SecurityMiddleware::generateCSRFToken('form_token');
// Use same token for multiple form fields

// ✅ Good: Cache security instances
$security = new SecurityMiddleware($config);
// Reuse instance for multiple operations

// ❌ Avoid: Generating new tokens unnecessarily
foreach ($formFields as $field) {
    $token = SecurityMiddleware::generateCSRFToken(); // Wasteful
}
```

## Examples

### Complete Form Protection

```php
// Generate form with CSRF protection
$csrfToken = SecurityMiddleware::generateCSRFToken('user_profile', 3600);
?>

<form method="POST" action="/update-profile">
    <?= SecurityMiddleware::csrfField('user_profile', 3600) ?>
    
    <input type="text" name="name" placeholder="Full Name">
    <input type="email" name="email" placeholder="Email">
    <textarea name="bio" placeholder="Biography"></textarea>
    
    <button type="submit">Update Profile</button>
</form>

<?php
// Process form submission
if ($_POST) {
    // Validate CSRF token
    if (!SecurityMiddleware::validateCSRFToken($_POST['user_profile'], 'user_profile', 3600)) {
        http_response_code(403);
        die('CSRF validation failed');
    }
    
    // Sanitize and validate input
    $userData = SecurityMiddleware::validateAndSanitize($_POST, [
        'trim' => true,
        'html_escape' => true,
        'xss_clean' => true,
        'max_length' => 255
    ]);
    
    // Process clean data
    updateUserProfile($userData);
}
```

### AJAX Protection

```php
// HTML head with CSRF meta tag
echo SecurityMiddleware::csrfMeta('ajax_token');
?>

<script>
// Get CSRF token from meta tag
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// AJAX request with CSRF protection
fetch('/api/update-data', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token
    },
    body: JSON.stringify({
        data: 'user input'
    })
});
</script>

<?php
// API endpoint
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    
    if (!SecurityMiddleware::validateCSRFToken($token, 'ajax_token')) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid CSRF token']);
        exit;
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    $cleanInput = SecurityMiddleware::cleanXSS($input);
    
    // Process clean input
    processApiRequest($cleanInput);
}
```

### Multi-Step Form

```php
// Step 1: Generate tokens for each step
$tokens = [
    'personal' => SecurityMiddleware::generateCSRFToken('wizard_personal', 7200),
    'payment' => SecurityMiddleware::generateCSRFToken('wizard_payment', 7200),
    'confirm' => SecurityMiddleware::generateCSRFToken('wizard_confirm', 7200)
];

// Store tokens in session for validation
$_SESSION['wizard_tokens'] = $tokens;

// Step 2: Validate each step
function validateWizardStep($step, $data) {
    $tokenKey = 'wizard_' . $step;
    $submittedToken = $data[$tokenKey] ?? '';
    
    if (!SecurityMiddleware::validateCSRFToken($submittedToken, $tokenKey, 7200)) {
        throw new Exception("Step {$step} CSRF validation failed");
    }
    
    return SecurityMiddleware::validateAndSanitize($data, [
        'xss_clean' => true,
        'max_length' => 500
    ]);
}
```

## Troubleshooting

### Common Issues

#### 1. CSRF Token Mismatch
```php
// Check token expiration
$token = SecurityMiddleware::generateCSRFToken('test', 60); // 1 minute
sleep(65); // Wait longer than expiration
$isValid = SecurityMiddleware::validateCSRFToken($token, 'test', 60);
// Returns false - token expired

// Solution: Use appropriate expiration times
$token = SecurityMiddleware::generateCSRFToken('form', 3600); // 1 hour
```

#### 2. Origin Validation Failing
```php
// Enable origin validation
$token = SecurityMiddleware::generateCSRFToken('secure', null, true);

// Must validate with same origin setting
$isValid = SecurityMiddleware::validateCSRFToken($token, 'secure', null, true);

// Common cause: Different IP or User Agent
// Solution: Only use origin validation for same-session requests
```

#### 3. XSS Cleaning Too Aggressive
```php
// Problem: AntiXSS removing valid HTML
$input = '<p>Valid paragraph</p>';
$cleaned = SecurityMiddleware::cleanXSS($input);
// May remove <p> tags

// Solution: Use allowed tags in validateAndSanitize
$cleaned = SecurityMiddleware::validateAndSanitize($input, [
    'allowed_tags' => '<p><br><strong><em>'
]);
```

#### 4. Rate Limiting Issues
```php
// Problem: Rate limit too strict
$security = new SecurityMiddleware(['rate_limit' => 10]); // Too low

// Solution: Adjust rate limits based on usage
$security = new SecurityMiddleware([
    'rate_limit' => 100,      // Reasonable limit
    'rate_window' => 60       // Per minute
]);
```

### Debug Helpers

```php
// Check if CSRF token is valid without consuming it
function debugCSRFToken($token, $key) {
    $isValid = SecurityMiddleware::validateCSRFToken($token, $key);
    
    echo "Token: " . substr($token, 0, 10) . "...\n";
    echo "Valid: " . ($isValid ? 'Yes' : 'No') . "\n";
    echo "Session key: " . $key . "\n";
    
    return $isValid;
}

// Test sanitization results
function debugSanitization($input) {
    echo "Original: " . var_export($input, true) . "\n";
    echo "Sanitized: " . var_export(
        SecurityMiddleware::sanitizeInput($input), true
    ) . "\n";
    echo "XSS Cleaned: " . var_export(
        SecurityMiddleware::cleanXSS($input), true
    ) . "\n";
}
```

### Performance Monitoring

```php
// Monitor sanitization performance
$start = microtime(true);
$cleanData = SecurityMiddleware::validateAndSanitize($largeDataset, [
    'xss_clean' => true
]);
$duration = microtime(true) - $start;

echo "Sanitization took: {$duration} seconds\n";
echo "Memory used: " . memory_get_peak_usage(true) . " bytes\n";
```

## Support & Contributing

For issues, questions, or contributions related to SecurityMiddleware:

1. Check this documentation for common solutions
2. Review the source code in `System/Middlewares/SecurityMiddleware.php`
3. Test your implementation with the provided examples
4. Report bugs with detailed reproduction steps

---

*NSY SecurityMiddleware*  
*Comprehensive security protection for modern web applications*
