# CodeIgniter Helpers Documentation

## Overview

This document provides comprehensive English documentation for all utility functions available in the NSY Framework's CodeIgniter Helpers collection. These functions have been optimized for performance, security, and modern PHP compatibility while maintaining backward compatibility with existing code.

## Table of Contents

1. [HTML & Attribute Functions](#html--attribute-functions)
2. [File System Functions](#file-system-functions)
3. [Array & Data Functions](#array--data-functions)
4. [Text Processing Functions](#text-processing-functions)
5. [String Manipulation Functions](#string-manipulation-functions)
6. [URL & Web Functions](#url--web-functions)
7. [Security Functions](#security-functions)
8. [Random Generation Functions](#random-generation-functions)
9. [File Permission Functions](#file-permission-functions)

---

## HTML & Attribute Functions

### `stringify_attributes($attributes, $js = false)`

Converts HTML attributes array/object to string format for use in HTML elements.

**Parameters:**
- `$attributes` (array|object|string|null) - The attributes to convert
- `$js` (bool) - Whether to format for JavaScript (comma-separated) or HTML (space-separated)

**Returns:** `string` - The formatted attribute string

**Examples:**
```php
// HTML format (default)
stringify_attributes(['class' => 'btn', 'id' => 'submit']);
// Returns: ' class="btn" id="submit"'

// JavaScript format
stringify_attributes(['width' => 100, 'height' => 200], true);
// Returns: 'width=100,height=200'

// With special characters (automatically escaped)
stringify_attributes(['title' => 'Click "here" & go']);
// Returns: ' title="Click &quot;here&quot; &amp; go"'
```

**Security Features:**
- Automatic HTML entity escaping to prevent XSS attacks
- Null and false value filtering
- UTF-8 safe encoding

---

## File System Functions

### `set_realpath($path, $check_existence = false)`

Resolves and validates file system paths with security checks.

**Parameters:**
- `$path` (string) - The path to resolve
- `$check_existence` (bool) - Whether to verify the path exists

**Returns:** `string` - The resolved absolute path

**Throws:** `InvalidArgumentException` - If path is invalid or doesn't exist when checking is enabled

**Examples:**
```php
// Basic path resolution
set_realpath('./config/../app.php');
// Returns: '/var/www/html/app.php'

// With existence check
set_realpath('/path/to/file.txt', true);
// Throws exception if file doesn't exist

// Directory paths get trailing slash
set_realpath('/var/www/html');
// Returns: '/var/www/html/'
```

**Security Features:**
- Prevents remote file inclusion (RFI) attacks
- Blocks directory traversal attempts
- Validates against IP addresses and dangerous protocols
- Comprehensive URL pattern detection

### `directory_map($source_dir, $directory_depth = 0, $hidden = false)`

Creates a recursive directory structure map as a nested array.

**Parameters:**
- `$source_dir` (string) - Path to the directory to map
- `$directory_depth` (int) - Maximum depth to traverse (0 = unlimited)
- `$hidden` (bool) - Whether to include hidden files/directories

**Returns:** `array|false` - Directory structure array, or false on failure

**Examples:**
```php
// Basic directory mapping
directory_map('/var/www/html');
// Returns: ['index.php', 'assets/' => ['css/' => ['style.css'], 'js/' => ['app.js']]]

// Limited depth
directory_map('/var/www/html', 2);
// Only maps 2 levels deep

// Include hidden files
directory_map('/var/www/html', 0, true);
// Includes .htaccess, .git/, etc.
```

**Performance Features:**
- Uses modern `DirectoryIterator` for better performance
- Efficient error handling and logging
- Memory-optimized recursive processing

---

## Array & Data Functions

### `random_element($array)`

Returns a random element from an array using cryptographically secure randomization.

**Parameters:**
- `$array` (array|mixed) - The array to select from, or any other value

**Returns:** `mixed` - Random element from array, or the input value if not an array

**Throws:** `InvalidArgumentException` - If array is empty

**Examples:**
```php
// Random selection from array
random_element(['apple', 'banana', 'cherry']);
// Returns: 'banana' (random selection)

// Non-array input returns unchanged
random_element('not_array');
// Returns: 'not_array'

// Empty array throws exception
random_element([]);
// Throws InvalidArgumentException
```

**Security Features:**
- Uses `random_int()` for cryptographically secure selection
- Fallback to `array_rand()` if secure functions unavailable
- Proper error handling for edge cases

---

## Text Processing Functions

### `word_limiter($str, $limit = 100, $end_char = '&#8230;')`

Truncates text to a specified number of words while preserving word boundaries.

**Parameters:**
- `$str` (string) - The input string to limit
- `$limit` (int) - Maximum number of words to keep (default: 100)
- `$end_char` (string) - Character(s) to append when truncated (default: '&#8230;' - ellipsis)

**Returns:** `string` - The word-limited string

**Examples:**
```php
// Basic word limiting
word_limiter('The quick brown fox jumps over the lazy dog', 5);
// Returns: 'The quick brown fox jumps&#8230;'

// Custom ending character
word_limiter('Hello world from PHP', 2, '...');
// Returns: 'Hello world...'

// No truncation needed
word_limiter('Short text', 10);
// Returns: 'Short text' (no ellipsis added)
```

**Performance Features:**
- Optimized regex pattern for faster word matching
- UTF-8 safe string operations
- Single regex operation instead of multiple string functions

### `character_limiter($str, $n = 500, $end_char = '&#8230;')`

Truncates text to specified character count while attempting to preserve complete words.

**Parameters:**
- `$str` (string) - The input string to limit
- `$n` (int) - Maximum character count (default: 500)
- `$end_char` (string) - Character(s) to append when truncated

**Returns:** `string` - The character-limited string

**Examples:**
```php
// Character limiting with word preservation
character_limiter('Hello world! This is a test.', 15);
// Returns: 'Hello world!&#8230;' (preserves word boundaries)

// Whitespace normalization
character_limiter("Multiple\n\tspaces  here", 15);
// Returns: 'Multiple spaces&#8230;'

// Custom ending
character_limiter('Long sentence here', 12, '..');
// Returns: 'Long sentence..'
```

**Features:**
- Single-pass whitespace normalization
- UTF-8 safe multibyte operations
- Intelligent word boundary detection

### `ascii_to_entities($str)`

Converts high ASCII and multibyte characters to HTML entities.

**Parameters:**
- `$str` (string) - The input string to convert

**Returns:** `string` - String with high ASCII characters converted to HTML entities

**Examples:**
```php
// Convert accented characters
ascii_to_entities('café');
// Returns: 'caf&#233;'

// Multiple special characters
ascii_to_entities('naïve résumé');
// Returns: 'na&#239;ve r&#233;sum&#233;'

// Currency symbols
ascii_to_entities('Price: £50');
// Returns: 'Price: &#163;50'
```

**Features:**
- Proper UTF-8 multibyte sequence handling
- Support for 2, 3, and 4-byte UTF-8 characters
- MS Word special character compatibility

---

## String Manipulation Functions

### `encode_php_tags($str)`

Encodes PHP tags to HTML entities for safe display in HTML context.

**Parameters:**
- `$str` (string) - The string containing PHP tags to encode

**Returns:** `string` - String with PHP tags converted to HTML entities

**Examples:**
```php
// Basic PHP tag encoding
encode_php_tags('<?php echo "Hello"; ?>');
// Returns: '&lt;?php echo "Hello"; ?&gt;'

// Short tags and ASP-style tags
encode_php_tags('<?= $variable ?> <% asp_code %>');
// Returns: '&lt;?= $variable ?&gt; &lt;% asp_code %&gt;'
```

### `increment_string($str, $separator = '_', $first = 1)`

Adds or increments a numeric suffix to a string for creating unique identifiers.

**Parameters:**
- `$str` (string) - The base string to increment
- `$separator` (string) - The separator character (default: '_')
- `$first` (int) - The starting number for first increment (default: 1)

**Returns:** `string` - The incremented string

**Examples:**
```php
// First increment
increment_string('file');
// Returns: 'file_1'

// Increment existing number
increment_string('file_3');
// Returns: 'file_4'

// Custom separator and starting number
increment_string('item', '-', 5);
// Returns: 'item-5'

// Increment with custom separator
increment_string('data-10', '-');
// Returns: 'data-11'
```

### `alternator(...$values)`

Cycles through provided values on each call, useful for alternating patterns.

**Parameters:**
- `...$values` (string) - Variable number of values to alternate between

**Returns:** `string` - The next value in the alternation sequence

**Examples:**
```php
// Basic alternation
echo alternator('red', 'blue');     // Returns: 'red' (first call)
echo alternator('red', 'blue');     // Returns: 'blue' (second call)
echo alternator('red', 'blue');     // Returns: 'red' (third call)

// Reset counter
alternator();                       // Reset (returns empty string)

// Practical usage for table rows
foreach ($data as $row) {
    $class = alternator('even', 'odd');
    echo "<tr class='$class'>...</tr>";
}
```

### `reduce_multiples($str, $character = ',', $trim = false)`

Reduces multiple instances of a particular character to single instances.

**Parameters:**
- `$str` (string) - The input string
- `$character` (string) - The character to reduce (default: ',')
- `$trim` (bool) - Whether to trim the character from beginning/end

**Examples:**
```php
// Reduce multiple commas
reduce_multiples('Fred, Bill,, Joe, Jimmy');
// Returns: 'Fred, Bill, Joe, Jimmy'

// Reduce with trimming
reduce_multiples(',,Fred, Bill,, Joe,,', ',', true);
// Returns: 'Fred, Bill, Joe'
```

---

## URL & Web Functions

### `prep_url($str = '')`

Adds HTTP scheme to URLs missing protocol with security validation.

**Parameters:**
- `$str` (string) - The URL to prepare

**Returns:** `string` - The URL with scheme, or empty string if invalid

**Examples:**
```php
// Add HTTP to domain
prep_url('example.com');
// Returns: 'http://example.com'

// Preserve existing schemes
prep_url('https://secure.com');
// Returns: 'https://secure.com'

prep_url('ftp://files.com');
// Returns: 'ftp://files.com'

// Handle invalid URLs
prep_url('');
// Returns: ''

prep_url('http://');
// Returns: ''
```

**Security Features:**
- URL format validation
- Malicious URL injection prevention
- Graceful handling of malformed URLs

### `url_title($str, $separator = '-', $lowercase = false)`

Converts text to SEO-friendly URL slugs.

**Parameters:**
- `$str` (string) - The input string to convert
- `$separator` (string) - Word separator character (default: '-')
- `$lowercase` (bool) - Whether to convert to lowercase (default: false)

**Returns:** `string` - The URL-friendly slug

**Examples:**
```php
// Basic URL slug creation
url_title('Hello World!');
// Returns: 'Hello-World'

// With custom separator and lowercase
url_title('My Amazing Article', '_', true);
// Returns: 'my_amazing_article'

// Remove special characters
url_title('Special chars: @#$%', '-', true);
// Returns: 'special-chars'

// Handle HTML tags
url_title('<h1>HTML Title</h1>');
// Returns: 'HTML-Title'
```

**Features:**
- UTF-8 safe multibyte operations
- HTML tag removal for security
- Comprehensive special character handling
- SEO-optimized output

---

## Security Functions

### `word_censor($str, $censored, $replacement = '')`

Censors disallowed words in text with replacement characters or custom text.

**Parameters:**
- `$str` (string) - The text string to censor
- `$censored` (array) - Array of words to censor
- `$replacement` (string) - Optional replacement text (default: converts to ####)

**Examples:**
```php
// Basic censoring with ### replacement
word_censor('This is bad text', ['bad']);
// Returns: 'This is ### text'

// Custom replacement text
word_censor('This is bad text', ['bad'], '[CENSORED]');
// Returns: 'This is [CENSORED] text'
```

### `xml_convert($str, $protect_all = false)`

Converts reserved XML characters to entities while preserving existing valid entities.

**Parameters:**
- `$str` (string) - The string to convert
- `$protect_all` (bool) - Whether to protect all existing HTML entities

**Examples:**
```php
// Basic XML conversion
xml_convert('<tag>Data & "value"</tag>');
// Returns: '&lt;tag&gt;Data &amp; &quot;value&quot;&lt;/tag&gt;'

// Protect existing entities
xml_convert('Already &amp; encoded', true);
// Returns: 'Already &amp; encoded' (preserves existing &amp;)
```

---

## Random Generation Functions

### `random_string($type = 'alnum', $len = 8)`

Generates cryptographically secure random strings for various purposes.

**Parameters:**
- `$type` (string) - Type of random string: 'alpha', 'alnum', 'numeric', 'nozero', 'md5', 'sha1', 'crypto', 'basic'
- `$len` (int) - Desired length of the generated string

**Returns:** `string` - Generated random string

**Examples:**
```php
// Alphanumeric string
random_string('alnum', 16);
// Returns: 'A7b9Kx2m4N8qW3zY'

// Alphabetic only
random_string('alpha', 10);
// Returns: 'AbCdEfGhIj'

// Numeric only
random_string('numeric', 6);
// Returns: '123456'

// Cryptographic hex string
random_string('crypto', 32);
// Returns: 64-character hex string

// Hash-based strings
random_string('md5');
// Returns: 32-character MD5 hash

random_string('sha1');
// Returns: 40-character SHA1 hash
```

**Security Features:**
- Uses `random_bytes()` and `random_int()` for cryptographic security
- Suitable for passwords, tokens, and security purposes
- Multiple output formats for different use cases

---

## File Permission Functions

### `symbolic_permissions($perms)`

Converts numeric file permissions to human-readable symbolic notation.

**Parameters:**
- `$perms` (int) - Numeric permissions value (from `fileperms()`)

**Returns:** `string` - Symbolic permission string (10 characters)

**Examples:**
```php
// Convert octal permissions
symbolic_permissions(0755);
// Returns: '-rwxr-xr-x'

// Directory permissions
symbolic_permissions(fileperms('/path/to/dir'));
// Returns: 'drwxr-xr-x'

// File with special permissions
symbolic_permissions(04755);
// Returns: '-rwsr-xr-x' (setuid bit)
```

### `octal_permissions($perms)`

Extracts permission bits and returns them as a three-digit octal string.

**Parameters:**
- `$perms` (int) - Numeric permissions value (from `fileperms()`)

**Returns:** `string` - Three-digit octal permissions string

**Examples:**
```php
// Extract octal permissions
octal_permissions(fileperms('/path/to/file'));
// Returns: '644'

octal_permissions(0755);
// Returns: '755'
```

---

## Additional Utility Functions

### `entities_to_ascii($str, $all = true)`
Converts HTML entities back to ASCII characters.

### `highlight_code($str)`
Applies PHP syntax highlighting to code strings.

### `highlight_phrase($str, $phrase, $tag_open = '<mark>', $tag_close = '</mark>')`
Highlights specific phrases within text.

### `word_wrap($str, $charlim = 76)`
Wraps text at specified character limit while preserving word integrity.

### `ellipsize($str, $max_length, $position = 1, $ellipsis = '&hellip;')`
Truncates strings with ellipsis at specified position.

### `strip_slashes($str)`
Removes slashes from strings or arrays recursively.

### `strip_quotes($str)`
Removes single and double quotes from strings.

### `quotes_to_entities($str)`
Converts quotes to HTML entities.

### `reduce_double_slashes($str)`
Converts double slashes to single slashes (except in URLs).

---

## Performance & Security Notes

### Performance Optimizations
- **UTF-8 Safe Operations**: All string functions use multibyte-safe operations
- **Regex Optimization**: Pre-compiled patterns and efficient matching
- **Memory Efficiency**: Reduced string concatenations and temporary variables
- **Early Returns**: Avoid unnecessary processing for edge cases

### Security Features
- **Input Validation**: Comprehensive parameter checking with exceptions
- **XSS Prevention**: Automatic HTML entity escaping where appropriate
- **Path Security**: Directory traversal and RFI protection
- **Cryptographic Security**: Secure random generation for sensitive operations

### Compatibility
- **PHP 7.0+**: Modern PHP features with backward compatibility
- **UTF-8 Support**: Full international character support
- **Exception Handling**: Proper error handling with meaningful messages
- **Type Safety**: Strict type hints and return type declarations

---

## Usage Examples

### Creating SEO-Friendly URLs
```php
$title = "My Amazing Blog Post: Tips & Tricks!";
$slug = url_title($title, '-', true);
// Result: 'my-amazing-blog-post-tips-tricks'
```

### Generating Secure Tokens
```php
$csrf_token = random_string('crypto', 32);
$session_id = random_string('alnum', 40);
$password = random_string('alnum', 12);
```

### Processing User Content
```php
$content = "User input with <script>alert('xss')</script> content";
$safe_content = encode_php_tags($content);
$preview = word_limiter($safe_content, 20);
```

### File System Operations
```php
$safe_path = set_realpath($_GET['file'], true);
$dir_structure = directory_map($safe_path, 2);
$permissions = symbolic_permissions(fileperms($safe_path));
```

This documentation covers all functions in the CodeIgniter Helpers collection, providing comprehensive usage examples, security considerations, and performance notes for effective implementation in NSY Framework applications.
