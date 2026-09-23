# CodeIgniter Helpers Documentation

## Overview

This document provides complete tutorial documentation for all utility functions in the NSY Framework's CodeIgniter Helpers collection (`System/Helpers/CodeIgniterHelpers.php`). All helpers are global functions, auto-loaded via `composer.json` (`files`), and safe to include multiple times.

> **Usage:** no `use` statement is needed — call the functions directly.

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
10. [Additional Utility Functions](#additional-utility-functions)

---

## HTML & Attribute Functions

### `stringify_attributes($attributes, $js = false): string` — `CodeIgniterHelpers.php:30`

Converts an array, object, or string of HTML attributes into a formatted string.

**Parameters:**
- `$attributes` (array|object|string|null) - attributes to convert
- `$js` (bool) - `false` for HTML (` class="btn"`), `true` for JavaScript (`width=100,height=200`)

**Returns:** `string`

**Examples:**
```php
// HTML
stringify_attributes(['class' => 'btn', 'id' => 'submit']);
// Returns: ' class="btn" id="submit"'

// JavaScript
stringify_attributes(['width' => 100, 'height' => 200], true);
// Returns: 'width=100,height=200'

// Special characters are escaped
stringify_attributes(['title' => 'Click "here" & go']);
// Returns: ' title="Click &quot;here&quot; &amp; go"'

// Null and false values are skipped
stringify_attributes(['a' => null, 'b' => false, 'c' => 'ok']);
// Returns: ' c="ok"'

// Objects and strings
stringify_attributes((object)['k' => 'v']); // ' k="v"'
stringify_attributes('class="x"');         // ' class="x"'
```

**Notes:**
- HTML mode escapes with `htmlspecialchars(ENT_QUOTES, UTF-8)`.
- JavaScript mode escapes quotes and commas.
- Empty input returns `''`.

---

## File System Functions

### `set_realpath(string $path, bool $check_existence = false): string` — `CodeIgniterHelpers.php:99`

Resolves a file system path to its absolute form and validates it.

**Parameters:**
- `$path` (string) - path to resolve
- `$check_existence` (bool) - throw if the path does not exist

**Returns:** `string` — absolute path (directories end with `DIRECTORY_SEPARATOR`)

**Throws:** `InvalidArgumentException`

**Examples:**
```php
set_realpath('./config/../app.php');
// Returns: '/var/www/html/app.php'

set_realpath('/path/to/file.txt', true);
// Throws InvalidArgumentException if the file does not exist

set_realpath('/var/www/html');
// Returns: '/var/www/html/'
```

**Security:**
- Blocks remote URLs (`https://`, `http://`, `ftp://`, `php://`, `file://`, `data:`, `javascript:`, `www.`).
- Rejects IP addresses.
- Traversal like `../` is resolved via `realpath()`.

### `directory_map(string $source_dir, int $directory_depth = 0, bool $hidden = false)` — `CodeIgniterHelpers.php:199`

Creates a nested array map of a directory.

**Parameters:**
- `$source_dir` (string) - directory to scan
- `$directory_depth` (int) - max depth (`0` = unlimited)
- `$hidden` (bool) - include hidden files

**Returns:** `array|false`

**Examples:**
```php
directory_map('/var/www/html');
// ['index.php', 'assets/' => ['css/' => ['style.css']]]

directory_map('/var/www/html', 2);
// Only 2 levels deep

directory_map('/var/www/html', 0, true);
// Includes .htaccess, .git/
```

---

## Array & Data Functions

### `random_element($array)` — `CodeIgniterHelpers.php:157`

Returns a random element from an array.

**Parameters:**
- `$array` (array|mixed) - array to pick from, or any other value

**Returns:** `mixed` — random element, or the input unchanged if not an array

**Throws:** `InvalidArgumentException` if the array is empty

**Examples:**
```php
random_element(['apple', 'banana', 'cherry']); // random pick
random_element('not_array'); // Returns: 'not_array'
random_element([]); // Throws InvalidArgumentException
```

---

## Text Processing Functions

### `word_limiter(string $str, int $limit = 100, string $end_char = '&#8230;'): string` — `CodeIgniterHelpers.php:306`

Truncates text to a number of words, preserving word boundaries.

**Parameters:**
- `$str` (string) - input text
- `$limit` (int) - max words
- `$end_char` (string) - appended when truncated

**Returns:** `string`

**Examples:**
```php
word_limiter('The quick brown fox jumps over the lazy dog', 5);
// 'The quick brown fox jumps&#8230;'

word_limiter('Hello world from PHP', 2, '...');
// 'Hello world...'

word_limiter('Short text', 10);
// 'Short text' (no truncation)
```

### `character_limiter(string $str, int $n = 500, string $end_char = '&#8230;'): string` — `CodeIgniterHelpers.php:368`

Truncates text to a character count while keeping whole words when possible.

**Parameters:**
- `$str` (string) - input text
- `$n` (int) - max characters
- `$end_char` (string) - appended when truncated

**Returns:** `string`

**Examples:**
```php
character_limiter('Hello world! This is a test.', 15);
// 'Hello world!&#8230;'

character_limiter("Multiple\n\tspaces  here", 15);
// 'Multiple spaces&#8230;'

character_limiter('Long sentence here', 12, '..');
// 'Long sentence..'
```

### `ascii_to_entities(string $str): string` — `CodeIgniterHelpers.php:441`

Converts high ASCII and multibyte characters to HTML entities.

**Parameters:**
- `$str` (string) - input text

**Returns:** `string`

**Examples:**
```php
ascii_to_entities('café');         // 'caf&#233;'
ascii_to_entities('naïve résumé'); // 'na&#239;ve r&#233;sum&#233;'
ascii_to_entities('Price: £50');   // 'Price: &#163;50'
ascii_to_entities('Hello World');  // 'Hello World' (pure ASCII, no conversion)
```

---

## String Manipulation Functions

### `encode_php_tags(string $str): string` — `CodeIgniterHelpers.php:266`

Encodes PHP tags to HTML entities for safe display.

**Parameters:**
- `$str` (string) - text containing PHP tags

**Returns:** `string`

**Examples:**
```php
encode_php_tags('<?php echo "Hello"; ?>');
// '&lt;?php echo "Hello"; ?&gt;'

encode_php_tags('<?= $variable ?> <% asp_code %>');
// '&lt;?= $variable ?&gt; &lt;% asp_code %&gt;'
```

### `increment_string(string $str, string $separator = '_', int $first = 1): string` — `CodeIgniterHelpers.php:1067`

Adds or increments a numeric suffix.

**Parameters:**
- `$str` (string) - base string
- `$separator` (string) - separator
- `$first` (int) - starting number

**Returns:** `string`

**Examples:**
```php
increment_string('file');        // 'file_1'
increment_string('file_3');      // 'file_4'
increment_string('item', '-', 5); // 'item-5'
increment_string('data-10', '-'); // 'data-11'
```

### `alternator(...$values): string` — `CodeIgniterHelpers.php:1126`

Cycles through values on each call.

**Parameters:**
- `...$values` (string) - values to alternate

**Returns:** `string` — next value, or `''` when called with no arguments (resets)

**Examples:**
```php
echo alternator('red', 'blue'); // 'red'
echo alternator('red', 'blue'); // 'blue'
echo alternator('red', 'blue'); // 'red'
alternator(); // reset

foreach ($data as $row) {
    $class = alternator('even', 'odd');
    echo "<tr class='$class'>...</tr>";
}
```

### `reduce_multiples(string $str, string $character = ',', bool $trim = false): string` — `CodeIgniterHelpers.php:959`

Reduces multiple instances of a character to one.

**Parameters:**
- `$str` (string) - input
- `$character` (string) - character to reduce
- `$trim` (bool) - trim from start/end

**Examples:**
```php
reduce_multiples('Fred, Bill,, Joe, Jimmy');
// 'Fred, Bill, Joe, Jimmy'

reduce_multiples(',,Fred, Bill,, Joe,,', ',', true);
// 'Fred, Bill, Joe'
```

---

## URL & Web Functions

### `prep_url(string $str = ''): string` — `CodeIgniterHelpers.php:1171`

Adds `http://` to URLs missing a scheme.

**Parameters:**
- `$str` (string) - URL

**Returns:** `string` — URL with scheme, or `''` if invalid

**Examples:**
```php
prep_url('example.com');      // 'http://example.com'
prep_url('https://secure.com'); // 'https://secure.com'
prep_url('ftp://files.com');    // 'ftp://files.com'
prep_url('');                 // ''
prep_url('http://');          // ''
prep_url('/path/to');         // '/path/to' (relative path preserved)
```

### `url_title(string $str, string $separator = '-', bool $lowercase = false): string` — `CodeIgniterHelpers.php:1231`

Converts text to an SEO-friendly URL slug.

**Parameters:**
- `$str` (string) - input text
- `$separator` (string) - word separator
- `$lowercase` (bool) - convert to lowercase

**Returns:** `string`

**Examples:**
```php
url_title('Hello World!');                 // 'Hello-World'
url_title('My Amazing Article', '_', true); // 'my_amazing_article'
url_title('Special chars: @#$%', '-', true); // 'special-chars'
url_title('<h1>HTML Title</h1>');           // 'HTML-Title'
```

---

## Security Functions

### `word_censor(string $str, array $censored, string $replacement = ''): string` — `CodeIgniterHelpers.php:577`

Censors disallowed words.

**Parameters:**
- `$str` (string) - text
- `$censored` (array) - words to censor
- `$replacement` (string) - custom replacement (default: `####`)

**Examples:**
```php
word_censor('This is bad text', ['bad']);
// 'This is ### text'

word_censor('This is bad text', ['bad'], '[CENSORED]');
// 'This is [CENSORED] text'
```

### `xml_convert(string $str, bool $protect_all = false): string` — `CodeIgniterHelpers.php:1291`

Converts XML reserved characters to entities.

**Parameters:**
- `$str` (string) - input
- `$protect_all` (bool) - preserve existing entities

**Returns:** `string`

**Examples:**
```php
xml_convert('<tag>Data & "value"</tag>');
// '&lt;tag&gt;Data &amp; &quot;value&quot;&lt;/tag&gt;'

xml_convert('Already &amp; encoded', true);
// 'Already &amp; encoded'
```

### `entities_to_ascii(string $str, bool $all = true): string` — `CodeIgniterHelpers.php:528`

Converts HTML entities back to characters.

**Parameters:**
- `$str` (string) - entity-encoded string
- `$all` (bool) - also decode named entities

**Returns:** `string`

**Examples:**
```php
entities_to_ascii('caf&#233;');              // 'café'
entities_to_ascii('Tom &amp; Jerry', true);  // 'Tom & Jerry'
entities_to_ascii('Tom &amp; Jerry', false); // 'Tom &amp; Jerry'
```

### `strip_slashes($str)` — `CodeIgniterHelpers.php:857`

Removes slashes from a string or array (recursive).

```php
strip_slashes('O\'Reilly');        // "O'Reilly"
strip_slashes(['a\'b', 'c\\d']);   // ['ab','cd']
```

### `strip_quotes(string $str): string` — `CodeIgniterHelpers.php:892`

Removes single and double quotes.

```php
strip_quotes('"hello" \'world\''); // 'hello world'
```

### `quotes_to_entities(string $str): string` — `CodeIgniterHelpers.php:908`

Converts quotes to HTML entities.

```php
quotes_to_entities('"hello" and \'world\'');
// '&quot;hello&quot; and &#39;world&#39;'
```

### `encode_php_tags(string $str): string`

See String Manipulation — encodes `<?php, <?=, <?, ?>, <%, %>` for safe display.

---

## Random Generation Functions

### `random_string(string $type = 'alnum', int $len = 8): string` — `CodeIgniterHelpers.php:985`

Generates random strings.

**Parameters:**
- `$type` (string) - `alpha`, `alnum`, `numeric`, `nozero`, `md5`, `sha1`, `crypto`, `basic`
- `$len` (int) - desired length

**Returns:** `string`

**Throws:** `InvalidArgumentException` if `$len <= 0` or `crypto` with odd length

**Examples:**
```php
random_string('alnum', 16);  // 'A7b9Kx2m4N8qW3zY' (16 chars)
random_string('alpha', 10);  // 'AbCdEfGhIj'
random_string('numeric', 6); // '123456'
random_string('nozero', 6);  // '123456' (no zeros)
random_string('crypto', 32); // 32-char hex
random_string('md5');        // 32-char MD5
random_string('sha1');       // 40-char SHA1
random_string('basic', 8);   // '48392017' (8 digits)
random_string('basic', 16);  // 16 digits
```

---

## File Permission Functions

### `symbolic_permissions(int $perms): string` — `CodeIgniterHelpers.php:1332`

Converts numeric permissions to symbolic notation (e.g. `drwxr-xr-x`).

**Parameters:**
- `$perms` (int) - from `fileperms()`

**Returns:** `string` — 10 characters

**Examples:**
```php
symbolic_permissions(0755);                  // '-rwxr-xr-x' (with file type bits)
symbolic_permissions(fileperms('/path/dir')); // 'drwxr-xr-x'
symbolic_permissions(04755);                 // '-rwsr-xr-x'
```

### `octal_permissions(int $perms): string` — `CodeIgniterHelpers.php:1398`

Extracts permission bits as a three-digit octal string.

**Parameters:**
- `$perms` (int) - from `fileperms()`

**Returns:** `string`

**Examples:**
```php
octal_permissions(fileperms('/path/file')); // '644'
octal_permissions(0755); // '755'
```

---

## Additional Utility Functions

### `highlight_code(string $str): string` — `CodeIgniterHelpers.php:631`

Applies PHP syntax highlighting.

```php
highlight_code('echo "hi"; $x = 1;');
// '<code><span style="color: #...">echo...</span></code>'
```

### `highlight_phrase(string $str, string $phrase, string $tag_open = '<mark>', string $tag_close = '</mark>'): string` — `CodeIgniterHelpers.php:721`

Highlights a phrase (case-insensitive).

```php
highlight_phrase('Hello world, hello PHP', 'hello');
// '<mark>Hello</mark> world, <mark>hello</mark> PHP'
```

### `word_wrap(string $str, int $charlim = 76): string` — `CodeIgniterHelpers.php:739`

Wraps text at a character limit, preserving words and `{unwrap}` blocks. URLs are not force-broken.

```php
word_wrap(str_repeat('word ', 20), 10);
word_wrap('a {unwrap}do not wrap{/unwrap} b', 76);
```

### `ellipsize(string $str, int $max_length, $position = 1, string $ellipsis = '&hellip;'): string` — `CodeIgniterHelpers.php:830`

Truncates with an ellipsis at a given position.

```php
ellipsize('This is a very long string that needs truncating', 20);
// 'This is a very long&hellip;'
ellipsize('0123456789', 6, 0.5); // '012&hellip;789'
```

### `reduce_double_slashes(string $str): string` — `CodeIgniterHelpers.php:935`

Converts double slashes to single (except `http://`).

```php
reduce_double_slashes('http://www.site.com//index.php');
// 'http://www.site.com/index.php'
```

---

## Usage Examples

### Creating SEO-Friendly URLs

```php
$title = "My Amazing Blog Post: Tips & Tricks!";
$slug = url_title($title, '-', true);
// 'my-amazing-blog-post-tips-tricks'
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

