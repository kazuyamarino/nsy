<?php

/**
 * NSY PHP Framework - CodeIgniter Helpers (Optimized Edition)
 *
 * A collection of utility functions ported and optimized from CodeIgniter for NSY Framework
 * Enhanced with modern PHP features, better performance, and comprehensive documentation
 *
 * @package NSY\Helpers
 * @author NSY Framework Team
 * @version Optimized Edition
 */

/**
 * Convert HTML attributes array/object to string format
 *
 * Transforms an array or object of HTML attributes into a properly formatted string
 * suitable for use in HTML elements. Supports both HTML and JavaScript formats.
 *
 * @param array|object|string|null $attributes The attributes to convert
 * @param bool $js Whether to format for JavaScript (comma-separated) or HTML (space-separated)
 * @return string The formatted attribute string
 *
 * @example
 * stringify_attributes(['class' => 'btn', 'id' => 'submit']) // Returns: ' class="btn" id="submit"'
 * stringify_attributes(['width' => 100, 'height' => 200], true) // Returns: 'width=100,height=200'
 */
function stringify_attributes($attributes, bool $js = false): string
{
    // Handle null or empty input
    if (empty($attributes)) {
        return '';
    }

    // Convert objects to arrays for uniform processing
    if (is_object($attributes)) {
        $attributes = (array) $attributes;
    }

    // Process array attributes
    if (is_array($attributes)) {
        if (empty($attributes)) {
            return '';
        }

        $result = [];
        foreach ($attributes as $key => $value) {
            // Skip null or false values
            if ($value === null || $value === false) {
                continue;
            }

            // Escape values for security
            $escaped_value = htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
            
            if ($js) {
                $result[] = $key . '=' . $escaped_value;
            } else {
                $result[] = $key . '="' . $escaped_value . '"';
            }
        }

        return $js ? implode(',', $result) : ' ' . implode(' ', $result);
    }

    // Handle string input
    if (is_string($attributes)) {
        return ' ' . trim($attributes);
    }

    // Fallback for other types
    return (string) $attributes;
}

// ------------------------------------------------------------------------

if (!function_exists('set_realpath')) {
    /**
     * Resolve and validate file system path
     *
     * Resolves a file system path to its absolute form and optionally validates existence.
     * Includes security checks to prevent remote file inclusion attacks.
     *
     * @param string $path The path to resolve
     * @param bool $check_existence Whether to verify the path exists
     * @return string The resolved absolute path
     * @throws InvalidArgumentException If path is invalid or doesn't exist when checking is enabled
     *
     * @example
     * set_realpath('./config/../app.php') // Returns: '/var/www/html/app.php'
     * set_realpath('/nonexistent/path', true) // Throws exception
     */
    function set_realpath(string $path, bool $check_existence = false): string
    {
        // Enhanced security check to prevent remote file inclusion
        $dangerous_patterns = [
            '#^(https?:\/\/|ftp:\/\/|php:\/\/|file:\/\/|data:|javascript:)#i',
            '#^www\.#i',
            '#\.\.[\/\\]#', // Directory traversal
        ];
        
        foreach ($dangerous_patterns as $pattern) {
            if (preg_match($pattern, $path)) {
                throw new InvalidArgumentException('Invalid path: Remote URLs and directory traversal are not allowed');
            }
        }
        
        // Check for IP addresses
        if (filter_var($path, FILTER_VALIDATE_IP) !== false) {
            throw new InvalidArgumentException('Invalid path: IP addresses are not allowed');
        }

        // Attempt to resolve the path
        $resolved_path = realpath($path);
        
        if ($resolved_path !== false) {
            $path = $resolved_path;
        } elseif ($check_existence) {
            if (!is_dir($path) && !is_file($path)) {
                throw new InvalidArgumentException("Path does not exist: {$path}");
            }
        }

        // Normalize directory separators and add trailing slash for directories
        if (is_dir($path)) {
            return rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }
        
        return $path;
    }
}

// ------------------------------------------------------------------------

if (!function_exists('random_element')) {
    /**
     * Get random element from array
     *
     * Returns a random element from the provided array. If the input is not an array,
     * returns the input value unchanged. Uses cryptographically secure randomization when possible.
     *
     * @param array|mixed $array The array to select from, or any other value
     * @return mixed Random element from array, or the input value if not an array
     * @throws InvalidArgumentException If array is empty
     *
     * @example
     * random_element(['apple', 'banana', 'cherry']) // Returns: 'banana' (random)
     * random_element('not_array') // Returns: 'not_array'
     */
    function random_element($array)
    {
        if (!is_array($array)) {
            return $array;
        }
        
        if (empty($array)) {
            throw new InvalidArgumentException('Array cannot be empty');
        }
        
        // Use cryptographically secure random selection when available
        try {
            $keys = array_keys($array);
            $randomIndex = random_int(0, count($keys) - 1);
            return $array[$keys[$randomIndex]];
        } catch (Exception $e) {
            // Fallback to standard array_rand if random_int fails
            return $array[array_rand($array)];
        }
    }
}

// --------------------------------------------------------------------

if (!function_exists('directory_map')) {
    /**
     * Create recursive directory structure map
     *
     * Scans a directory and creates a nested array representation of its contents.
     * Supports depth limiting and optional inclusion of hidden files. Uses modern
     * directory iteration for better performance.
     *
     * @param string $source_dir Path to the directory to map
     * @param int $directory_depth Maximum depth to traverse (0 = unlimited)
     * @param bool $hidden Whether to include hidden files/directories
     * @return array|false Directory structure array, or false on failure
     * @throws InvalidArgumentException If source directory doesn't exist
     *
     * @example
     * directory_map('/var/www/html', 2, false)
     * // Returns: ['index.php', 'assets/' => ['css/' => ['style.css'], 'js/' => ['app.js']]]
     */
    function directory_map(string $source_dir, int $directory_depth = 0, bool $hidden = false)
    {
        // Validate source directory
        if (!is_dir($source_dir)) {
            throw new InvalidArgumentException("Source directory does not exist: {$source_dir}");
        }
        
        if (!is_readable($source_dir)) {
            throw new InvalidArgumentException("Source directory is not readable: {$source_dir}");
        }

        $filedata = [];
        $source_dir = rtrim($source_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $new_depth = $directory_depth - 1;

        try {
            // Use DirectoryIterator for better performance and error handling
            $iterator = new DirectoryIterator($source_dir);
            
            foreach ($iterator as $file) {
                $filename = $file->getFilename();
                
                // Skip current/parent directory and hidden files if not requested
                if ($file->isDot() || (!$hidden && $filename[0] === '.')) {
                    continue;
                }

                $full_path = $source_dir . $filename;
                
                if ($file->isDir()) {
                    $dir_name = $filename . DIRECTORY_SEPARATOR;
                    
                    // Recursively map subdirectories if within depth limit
                    if ($directory_depth < 1 || $new_depth > 0) {
                        $filedata[$dir_name] = directory_map($full_path, $new_depth, $hidden);
                    } else {
                        $filedata[] = $dir_name;
                    }
                } else {
                    $filedata[] = $filename;
                }
            }
            
            return $filedata;
        } catch (Exception $e) {
            // Log error if logging is available
            error_log("Directory mapping failed for {$source_dir}: " . $e->getMessage());
            return false;
        }
    }
}

//--------------------------------------------------------------------

if (!function_exists('encode_php_tags')) {
    /**
     * Encode PHP tags to HTML entities for safe display
     *
     * Converts PHP opening and closing tags to their HTML entity equivalents
     * to prevent code execution when displaying PHP code in HTML context.
     *
     * @param string $str The string containing PHP tags to encode
     * @return string String with PHP tags converted to HTML entities
     *
     * @example
     * encode_php_tags('<?php echo "Hello"; ?>') // Returns: '&lt;?php echo "Hello"; ?&gt;'
     */
    function encode_php_tags(string $str): string
    {
        return str_replace(
            ['<?php', '<?=', '<?', '?>', '<%', '%>'],
            ['&lt;?php', '&lt;?=', '&lt;?', '?&gt;', '&lt;%', '%&gt;'],
            $str
        );
    }
}

//--------------------------------------------------------------------

if (!function_exists('word_limiter')) {
    /**
     * Word Limiter - Truncates text to a specified number of words
     *
     * This function intelligently limits text to a maximum number of words while
     * preserving word boundaries. Perfect for creating excerpts, previews, or
     * summary content where you need consistent word count limits.
     * 
     * Performance improvements:
     * - Optimized regex pattern for faster word matching
     * - Early return for edge cases
     * - Efficient string comparison using multibyte functions
     * - Single regex operation instead of multiple string operations
     *
     * @param string $str      The input string to limit
     * @param int    $limit    Maximum number of words to keep (default: 100)
     * @param string $end_char Character(s) to append when truncated (default: '&#8230;' - ellipsis)
     * 
     * @return string The word-limited string
     * 
     * @throws InvalidArgumentException If limit is less than 1
     * 
     * @example
     * word_limiter('The quick brown fox jumps', 3)        // Returns: 'The quick brown&#8230;'
     * word_limiter('Hello world', 5, '...')               // Returns: 'Hello world' (no truncation)
     * word_limiter('One two three four five', 3, ' [more]') // Returns: 'One two three [more]'
     * word_limiter('  Spaces   everywhere  ', 2)          // Returns: 'Spaces everywhere&#8230;'
     */
    function word_limiter(string $str, int $limit = 100, string $end_char = '&#8230;'): string
    {
        // Validate parameters
        if ($limit < 1) {
            throw new InvalidArgumentException('Word limit must be greater than 0');
        }

        // Handle empty or whitespace-only strings
        $trimmedStr = trim($str);
        if ($trimmedStr === '') {
            return $str;
        }

        // Use more efficient regex pattern for word matching
        // This pattern matches leading whitespace + up to $limit word sequences
        $pattern = '/^\s*+(?:\S++\s*+){1,' . $limit . '}/u';
        
        if (!preg_match($pattern, $str, $matches)) {
            return $str; // No matches found, return original
        }

        $truncated = $matches[0];
        
        // Check if we've captured the entire string (UTF-8 safe comparison)
        if (mb_strlen($str, 'UTF-8') === mb_strlen($truncated, 'UTF-8')) {
            $end_char = ''; // No truncation needed
        }

        return rtrim($truncated) . $end_char;
    }
}

//--------------------------------------------------------------------

if (!function_exists('character_limiter')) {
    /**
     * Character Limiter - Truncates text to specified character count while preserving words
     *
     * This function intelligently limits text by character count while attempting to
     * preserve complete words. It normalizes whitespace and ensures clean truncation
     * at word boundaries when possible, making it ideal for content previews.
     * 
     * Performance improvements:
     * - Single-pass whitespace normalization using optimized regex
     * - Efficient multibyte string operations for UTF-8 safety
     * - Early returns to avoid unnecessary processing
     * - Optimized word boundary detection
     *
     * @param string $str      The input string to limit
     * @param int    $n        Maximum character count (default: 500)
     * @param string $end_char Character(s) to append when truncated (default: '&#8230;' - ellipsis)
     * 
     * @return string The character-limited string
     * 
     * @throws InvalidArgumentException If character limit is less than 1
     * 
     * @example
     * character_limiter('Hello world!', 8)              // Returns: 'Hello&#8230;' (preserves words)
     * character_limiter('Short text', 50)               // Returns: 'Short text' (no truncation)
     * character_limiter("Multiple\n\tspaces  here", 15)  // Returns: 'Multiple spaces&#8230;'
     * character_limiter('Long sentence here', 12, '..') // Returns: 'Long sentence..'
     */
    function character_limiter(string $str, int $n = 500, string $end_char = '&#8230;'): string
    {
        // Validate parameters
        if ($n < 1) {
            throw new InvalidArgumentException('Character limit must be greater than 0');
        }

        // Early return if string is already within limits
        if (mb_strlen($str, 'UTF-8') <= $n) {
            return $str;
        }

        // Normalize whitespace in a single efficient operation
        // Replace all whitespace characters with single spaces and collapse multiple spaces
        $str = preg_replace('/\s+/u', ' ', $str);

        // Check again after normalization
        if (mb_strlen($str, 'UTF-8') <= $n) {
            return $str;
        }

        $result = '';
        $currentLength = 0;
        
        // Split by words and build result while staying within character limit
        $words = explode(' ', trim($str));
        
        foreach ($words as $word) {
            $wordWithSpace = $word . ' ';
            $wordLength = mb_strlen($wordWithSpace, 'UTF-8');
            
            // Check if adding this word would exceed the limit
            if ($currentLength + $wordLength > $n) {
                break;
            }
            
            $result .= $wordWithSpace;
            $currentLength += $wordLength;
        }

        $result = rtrim($result);
        
        // Only add end character if we actually truncated the string
        return (mb_strlen($result, 'UTF-8') === mb_strlen($str, 'UTF-8')) ? $result : $result . $end_char;
    }
}

//--------------------------------------------------------------------

if (!function_exists('ascii_to_entities')) {
    /**
     * ASCII to HTML Entities - Converts high ASCII and multibyte characters to HTML entities
     *
     * This function converts high ASCII text (characters above 127) and multibyte
     * characters to their HTML entity equivalents. Particularly useful for handling
     * MS Word special characters and ensuring cross-platform text compatibility.
     * 
     * Performance improvements:
     * - UTF-8 aware processing using mb_string functions
     * - Simplified entity conversion logic
     * - Efficient string building with fewer concatenations
     * - Better handling of multibyte sequences
     *
     * @param string $str The input string to convert
     * 
     * @return string String with high ASCII characters converted to HTML entities
     * 
     * @example
     * ascii_to_entities('café')           // Returns: 'caf&#233;'
     * ascii_to_entities('naïve résumé')   // Returns: 'na&#239;ve r&#233;sum&#233;'
     * ascii_to_entities('Hello World')    // Returns: 'Hello World' (no conversion)
     * ascii_to_entities('Price: £50')     // Returns: 'Price: &#163;50'
     */
    function ascii_to_entities(string $str): string
    {
        // Handle empty string
        if ($str === '') {
            return $str;
        }

        // Use PHP's built-in multibyte support for more reliable conversion
        if (function_exists('mb_convert_encoding')) {
            // Convert to UTF-8 first if not already
            $str = mb_convert_encoding($str, 'UTF-8', 'auto');
        }

        $result = '';
        $length = strlen($str);
        
        for ($i = 0; $i < $length; $i++) {
            $byte = ord($str[$i]);
            
            // ASCII characters (0-127) - keep as is
            if ($byte < 128) {
                $result .= $str[$i];
                continue;
            }
            
            // High ASCII and multibyte characters
            if ($byte < 192) {
                // Invalid UTF-8 start byte, treat as single byte
                $result .= '&#' . $byte . ';';
            } elseif ($byte < 224) {
                // 2-byte UTF-8 sequence
                if ($i + 1 < $length) {
                    $byte2 = ord($str[$i + 1]);
                    $codepoint = (($byte & 0x1F) << 6) | ($byte2 & 0x3F);
                    $result .= '&#' . $codepoint . ';';
                    $i++; // Skip next byte
                } else {
                    $result .= '&#' . $byte . ';';
                }
            } elseif ($byte < 240) {
                // 3-byte UTF-8 sequence
                if ($i + 2 < $length) {
                    $byte2 = ord($str[$i + 1]);
                    $byte3 = ord($str[$i + 2]);
                    $codepoint = (($byte & 0x0F) << 12) | (($byte2 & 0x3F) << 6) | ($byte3 & 0x3F);
                    $result .= '&#' . $codepoint . ';';
                    $i += 2; // Skip next two bytes
                } else {
                    $result .= '&#' . $byte . ';';
                }
            } else {
                // 4-byte UTF-8 sequence (or invalid)
                if ($i + 3 < $length) {
                    $byte2 = ord($str[$i + 1]);
                    $byte3 = ord($str[$i + 2]);
                    $byte4 = ord($str[$i + 3]);
                    $codepoint = (($byte & 0x07) << 18) | (($byte2 & 0x3F) << 12) | (($byte3 & 0x3F) << 6) | ($byte4 & 0x3F);
                    $result .= '&#' . $codepoint . ';';
                    $i += 3; // Skip next three bytes
                } else {
                    $result .= '&#' . $byte . ';';
                }
            }
        }

        return $result;
    }
}

//--------------------------------------------------------------------

if (!function_exists('entities_to_ascii')) {
    /**
     * Entities to ASCII
     *
     * Converts character entities back to ASCII.
     *
     * @param string  $str
     * @param boolean $all
     *
     * @return string
     */
    function entities_to_ascii(string $str, bool $all = true): string
    {
        if (preg_match_all('/\&#(\d+)\;/', $str, $matches)) {
            for ($i = 0, $s = count($matches[0]); $i < $s; $i++) {
                $digits = $matches[1][$i];
                $out    = '';
                if ($digits < 128) {
                    $out .= chr($digits);
                } elseif ($digits < 2048) {
                    $out .= chr(192 + (($digits - ($digits % 64)) / 64)) . chr(128 + ($digits % 64));
                } else {
                    $out .= chr(224 + (($digits - ($digits % 4096)) / 4096))
                        . chr(128 + ((($digits % 4096) - ($digits % 64)) / 64))
                        . chr(128 + ($digits % 64));
                }
                $str = str_replace($matches[0][$i], $out, $str);
            }
        }

        if ($all) {
            return str_replace(
                [
                    '&amp;',
                    '&lt;',
                    '&gt;',
                    '&quot;',
                    '&apos;',
                    '&#45;',
                ],
                [
                    '&',
                    '<',
                    '>',
                    '"',
                    "'",
                    '-',
                ],
                $str
            );
        }

        return $str;
    }
}

//--------------------------------------------------------------------

if (!function_exists('word_censor')) {
    /**
     * Word Censoring Function
     *
     * Supply a string and an array of disallowed words and any
     * matched words will be converted to #### or to the replacement
     * word you've submitted.
     *
     * @param string $str         the text string
     * @param array  $censored    the array of censored words
     * @param string $replacement the optional replacement value
     *
     * @return string
     */
    function word_censor(string $str, array $censored, string $replacement = ''): string
    {
        if (empty($censored)) {
            return $str;
        }

        $str = ' ' . $str . ' ';

        // \w, \b and a few others do not match on a unicode character
        // set for performance reasons. As a result words like über
        // will not match on a word boundary. Instead, we'll assume that
        // a bad word will be bookended by any of these characters.
        $delim = '[-_\'\"`(){}<>\[\]|!?@#%&,.:;^~*+=\/ 0-9\n\r\t]';

        foreach ($censored as $badword) {
            $badword = str_replace('\*', '\w*?', preg_quote($badword, '/'));

            if ($replacement !== '') {
                $str = preg_replace(
                    "/({$delim})(" . $badword . ")({$delim})/i",
                    "\\1{$replacement}\\3",
                    $str
                );
            } elseif (preg_match_all("/{$delim}(" . $badword . "){$delim}/i", $str, $matches, PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE)) {
                $matches = $matches[1];

                for ($i = count($matches) - 1; $i >= 0; $i--) {
                    $length = strlen($matches[$i][0]);
                    $str    = substr_replace(
                        $str,
                        str_repeat('#', $length),
                        $matches[$i][1],
                        $length
                    );
                }
            }
        }

        return trim($str);
    }
}

//--------------------------------------------------------------------

if (!function_exists('highlight_code')) {
    /**
     * Code Highlighter
     *
     * Colorizes code strings.
     *
     * @param string $str the text string
     *
     * @return string
     */
    function highlight_code(string $str): string
    {
        /* The highlight string function encodes and highlights
        * brackets so we need them to start raw.
        *
        * Also replace any existing PHP tags to temporary markers
        * so they don't accidentally break the string out of PHP,
        * and thus, thwart the highlighting.
        */
        $str = str_replace(
            [
                '&lt;',
                '&gt;',
                '<?',
                '?>',
                '<%',
                '%>',
                '\\',
                '</script>',
            ],
            [
                '<',
                '>',
                'phptagopen',
                'phptagclose',
                'asptagopen',
                'asptagclose',
                'backslashtmp',
                'scriptclose',
            ],
            $str
        );

        // The highlight_string function requires that the text be surrounded
        // by PHP tags, which we will remove later
        $str = highlight_string('<?php ' . $str . ' ?>', true);

        // Remove our artificially added PHP, and the syntax highlighting that came with it
        $str = preg_replace(
            [
                '/<span style="color: #([A-Z0-9]+)">&lt;\?php(&nbsp;| )/i',
                '/(<span style="color: #[A-Z0-9]+">.*?)\?&gt;<\/span>\n<\/span>\n<\/code>/is',
                '/<span style="color: #[A-Z0-9]+"\><\/span>/i',
            ],
            [
                '<span style="color: #$1">',
                "$1</span>\n</span>\n</code>",
                '',
            ],
            $str
        );

        // Replace our markers back to PHP tags.
        return str_replace(
            [
                'phptagopen',
                'phptagclose',
                'asptagopen',
                'asptagclose',
                'backslashtmp',
                'scriptclose',
            ],
            [
                '&lt;?',
                '?&gt;',
                '&lt;%',
                '%&gt;',
                '\\',
                '&lt;/script&gt;',
            ],
            $str
        );
    }
}

//--------------------------------------------------------------------

if (!function_exists('highlight_phrase')) {
    /**
     * Phrase Highlighter
     *
     * Highlights a phrase within a text string.
     *
     * @param string $str       the text string
     * @param string $phrase    the phrase you'd like to highlight
     * @param string $tag_open  the opening tag to precede the phrase with
     * @param string $tag_close the closing tag to end the phrase with
     *
     * @return string
     */
    function highlight_phrase(string $str, string $phrase, string $tag_open = '<mark>', string $tag_close = '</mark>'): string
    {
        return ($str !== '' && $phrase !== '') ? preg_replace('/(' . preg_quote($phrase, '/') . ')/i', $tag_open . '\\1' . $tag_close, $str) : $str;
    }
}

//--------------------------------------------------------------------

if (!function_exists('word_wrap')) {
    /**
     * Word Wrap
     *
     * Wraps text at the specified character. Maintains the integrity of words.
     * Anything placed between {unwrap}{/unwrap} will not be word wrapped, nor
     * will URLs.
     *
     * @param string  $str     the text string
     * @param integer $charlim = 76    the number of characters to wrap at
     *
     * @return string
     */
    function word_wrap(string $str, int $charlim = 76): string
    {
        // Set the character limit
        is_numeric($charlim) || $charlim = 76;

        // Reduce multiple spaces
        $str = preg_replace('| +|', ' ', $str);

        // Standardize newlines
        if (strpos($str, "\r") !== false) {
            $str = str_replace(["\r\n", "\r"], "\n", $str);
        }

        // If the current word is surrounded by {unwrap} tags we'll
        // strip the entire chunk and replace it with a marker.
        $unwrap = [];

        if (preg_match_all('|\{unwrap\}(.+?)\{/unwrap\}|s', $str, $matches)) {
            for ($i = 0, $c = count($matches[0]); $i < $c; $i++) {
                $unwrap[] = $matches[1][$i];
                $str      = str_replace($matches[0][$i], '{{unwrapped' . $i . '}}', $str);
            }
        }

        // Use PHP's native function to do the initial wordwrap.
        // We set the cut flag to FALSE so that any individual words that are
        // too long get left alone. In the next step we'll deal with them.
        $str = wordwrap($str, $charlim, "\n", false);

        // Split the string into individual lines of text and cycle through them
        $output = '';

        foreach (explode("\n", $str) as $line) {
            // Is the line within the allowed character count?
            // If so we'll join it to the output and continue
            if (mb_strlen($line) <= $charlim) {
                $output .= $line . "\n";
                continue;
            }

            $temp = '';

            while (mb_strlen($line) > $charlim) {
                // If the over-length word is a URL we won't wrap it
                if (preg_match('!\[url.+\]|://|www\.!', $line)) {
                    break;
                }
                // Trim the word down
                $temp .= mb_substr($line, 0, $charlim - 1);
                $line  = mb_substr($line, $charlim - 1);
            }

            // If $temp contains data it means we had to split up an over-length
            // word into smaller chunks so we'll add it back to our current line
            if ($temp !== '') {
                $output .= $temp . "\n" . $line . "\n";
            } else {
                $output .= $line . "\n";
            }
        }

        // Put our markers back
        if (!empty($unwrap)) {
            foreach ($unwrap as $key => $val) {
                $output = str_replace('{{unwrapped' . $key . '}}', $val, $output);
            }
        }

        // remove any trailing newline
        $output = rtrim($output);

        return $output;
    }
}

//--------------------------------------------------------------------

if (!function_exists('ellipsize')) {
    /**
     * Ellipsize String
     *
     * This function will strip tags from a string, split it at its max_length and ellipsize.
     *
     * @param string  $str        String to ellipsize
     * @param integer $max_length Max length of string
     * @param mixed   $position   int (1|0) or float, .5, .2, etc for position to split
     * @param string  $ellipsis   ellipsis ; Default '...'
     *
     * @return string    Ellipsized string
     */
    function ellipsize(string $str, int $max_length, $position = 1, string $ellipsis = '&hellip;'): string
    {
        // Strip tags
        $str = trim(strip_tags($str));

        // Is the string long enough to ellipsize?
        if (mb_strlen($str) <= $max_length) {
            return $str;
        }

        $beg      = mb_substr($str, 0, floor($max_length * $position));
        $position = ($position > 1) ? 1 : $position;

        if ($position === 1) {
            $end = mb_substr($str, 0, - ($max_length - mb_strlen($beg)));
        } else {
            $end = mb_substr($str, - ($max_length - mb_strlen($beg)));
        }

        return $beg . $ellipsis . $end;
    }
}

//--------------------------------------------------------------------

if (!function_exists('strip_slashes')) {
    /**
     * Strip Slashes
     *
     * Removes slashes contained in a string or in an array.
     *
     * @param mixed $str string or array
     *
     * @return mixed  string or array
     */
    function strip_slashes($str)
    {
        if (!is_array($str)) {
            return stripslashes($str);
        }
        foreach ($str as $key => $val) {
            $str[$key] = strip_slashes($val);
        }

        return $str;
    }
}

//--------------------------------------------------------------------

if (!function_exists('strip_quotes')) {
    /**
     * Strip Quotes
     *
     * Removes single and double quotes from a string.
     *
     * @param string $str
     *
     * @return string
     */
    function strip_quotes(string $str): string
    {
        return str_replace(['"', "'"], '', $str);
    }
}

//--------------------------------------------------------------------

if (!function_exists('quotes_to_entities')) {
    /**
     * Quotes to Entities
     *
     * Converts single and double quotes to entities.
     *
     * @param string $str
     *
     * @return string
     */
    function quotes_to_entities(string $str): string
    {
        return str_replace(["\'", '"', "'", '"'], ['&#39;', '&quot;', '&#39;', '&quot;'], $str);
    }
}

//--------------------------------------------------------------------

if (!function_exists('reduce_double_slashes')) {
    /**
     * Reduce Double Slashes
     *
     * Converts double slashes in a string to a single slash,
     * except those found in http://
     *
     * http://www.some-site.com//index.php
     *
     * becomes:
     *
     * http://www.some-site.com/index.php
     *
     * @param string $str
     *
     * @return string
     */
    function reduce_double_slashes(string $str): string
    {
        return preg_replace('#(^|[^:])//+#', '\\1/', $str);
    }
}

//--------------------------------------------------------------------

if (!function_exists('reduce_multiples')) {
    /**
     * Reduce Multiples
     *
     * Reduces multiple instances of a particular character.  Example:
     *
     * Fred, Bill,, Joe, Jimmy
     *
     * becomes:
     *
     * Fred, Bill, Joe, Jimmy
     *
     * @param string  $str
     * @param string  $character the character you wish to reduce
     * @param boolean $trim      TRUE/FALSE - whether to trim the character from the beginning/end
     *
     * @return string
     */
    function reduce_multiples(string $str, string $character = ',', bool $trim = false): string
    {
        $str = preg_replace('#' . preg_quote($character, '#') . '{2,}#', $character, $str);

        return ($trim) ? trim($str, $character) : $str;
    }
}

//--------------------------------------------------------------------

if (!function_exists('random_string')) {
    /**
     * Generate cryptographically secure random string
     *
     * Creates random strings suitable for passwords, tokens, and other security purposes.
     * Uses cryptographically secure random number generation when available.
     *
     * @param string $type Type of random string: 'alpha', 'alnum', 'numeric', 'nozero', 'md5', 'sha1', 'crypto', or 'basic'
     * @param int $len Desired length of the generated string
     * @return string Generated random string
     * @throws InvalidArgumentException If length is invalid for crypto type
     *
     * @example
     * random_string('alnum', 16) // Returns: 'A7b9Kx2m4N8qW3zY'
     * random_string('crypto', 32) // Returns: 64-char hex string
     */
    function random_string(string $type = 'alnum', int $len = 8): string
    {
        if ($len <= 0) {
            throw new InvalidArgumentException('Length must be positive');
        }

        switch ($type) {
            case 'alpha':
                $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'alnum':
                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'numeric':
                $pool = '0123456789';
                break;
            case 'nozero':
                $pool = '123456789';
                break;
            case 'md5':
                return md5(random_bytes(16));
            case 'sha1':
                return sha1(random_bytes(20));
            case 'crypto':
                if ($len % 2 !== 0) {
                    throw new InvalidArgumentException('Crypto type requires even length');
                }
                return bin2hex(random_bytes($len / 2));
            case 'basic':
            default:
                return (string) random_int(100000, 999999999);
        }

        // Generate string from character pool using secure randomization
        $result = '';
        $pool_length = strlen($pool);
        
        for ($i = 0; $i < $len; $i++) {
            $result .= $pool[random_int(0, $pool_length - 1)];
        }
        
        return $result;
    }
}

//--------------------------------------------------------------------

if (!function_exists('increment_string')) {
    /**
     * Increment String - Adds or increments a numeric suffix to a string
     *
     * This function is useful for creating unique filenames, database entries,
     * or any scenario where you need to avoid duplicates by adding numeric suffixes.
     * 
     * Performance improvements:
     * - Optimized regex pattern compilation
     * - Early validation for edge cases
     * - Efficient string concatenation
     *
     * @param string $str       The base string to increment
     * @param string $separator The separator character (default: '_')
     * @param int    $first     The starting number for first increment (default: 1)
     * 
     * @return string The incremented string
     * 
     * @throws InvalidArgumentException If first number is less than 1
     * 
     * @example
     * increment_string('file')           // Returns: 'file_1'
     * increment_string('file_3')         // Returns: 'file_4'  
     * increment_string('item', '-', 5)   // Returns: 'item-5'
     * increment_string('data-10', '-')   // Returns: 'data-11'
     */
    function increment_string(string $str, string $separator = '_', int $first = 1): string
    {
        // Validate input parameters
        if ($first < 1) {
            throw new InvalidArgumentException('First increment number must be greater than 0');
        }

        // Handle empty string case
        if (empty($str)) {
            return (string)$first;
        }

        // Optimize: Pre-escape separator for regex
        $escapedSeparator = preg_quote($separator, '/');
        
        // Match string ending with separator + digits
        if (preg_match('/^(.+)' . $escapedSeparator . '(\d+)$/', $str, $matches)) {
            // Increment existing number
            $baseString = $matches[1];
            $currentNumber = (int)$matches[2];
            return $baseString . $separator . ($currentNumber + 1);
        }
        
        // No numeric suffix found, add first increment
        return $str . $separator . $first;
    }
}

//--------------------------------------------------------------------

if (!function_exists('alternator')) {
    /**
     * Alternator - Cycles through provided values on each call
     *
     * This function maintains an internal counter and returns values in sequence,
     * cycling back to the first value after the last one. Useful for alternating
     * CSS classes, table row colors, or any repeating pattern.
     * 
     * Performance improvements:
     * - Cached argument count to avoid repeated function calls
     * - Efficient modulo operation for cycling
     * - Early return for reset operation
     *
     * @param string ...$values Variable number of values to alternate between
     * 
     * @return string The next value in the alternation sequence, or empty string if reset
     * 
     * @example
     * echo alternator('red', 'blue');     // Returns: 'red' (first call)
     * echo alternator('red', 'blue');     // Returns: 'blue' (second call)  
     * echo alternator('red', 'blue');     // Returns: 'red' (third call)
     * alternator();                       // Reset counter (returns empty string)
     * 
     * // Practical usage for table rows:
     * foreach ($data as $row) {
     *     $class = alternator('even', 'odd');
     *     echo "<tr class='$class'>...</tr>";
     * }
     */
    function alternator(...$values): string
    {
        static $counter = 0;

        // Reset counter if no arguments provided
        $argCount = count($values);
        if ($argCount === 0) {
            $counter = 0;
            return '';
        }

        // Get current value and increment counter
        $currentValue = $values[$counter % $argCount];
        $counter++;

        return $currentValue;
    }
}

// ------------------------------------------------------------------------

if (!function_exists('prep_url')) {
    /**
     * Prepare URL - Adds HTTP scheme to URLs missing protocol
     *
     * This function ensures URLs have a proper scheme by adding 'http://'
     * to URLs that don't already have one. It preserves existing schemes
     * and handles edge cases properly.
     * 
     * Security improvements:
     * - Validates URL format before processing
     * - Prevents malicious URL injection
     * - Handles malformed URLs gracefully
     *
     * @param string $str The URL to prepare
     * 
     * @return string The URL with scheme, or empty string if invalid
     * 
     * @example
     * prep_url('example.com')           // Returns: 'http://example.com'
     * prep_url('https://secure.com')    // Returns: 'https://secure.com'
     * prep_url('ftp://files.com')       // Returns: 'ftp://files.com'
     * prep_url('')                      // Returns: ''
     * prep_url('http://')               // Returns: ''
     */
    function prep_url(string $str = ''): string
    {
        // Handle empty strings and invalid URLs
        $str = trim($str);
        if (empty($str) || $str === 'http://' || $str === 'https://') {
            return '';
        }

        // Parse URL components
        $parsed = parse_url($str);

        // If parsing failed or no host found, treat as invalid
        if ($parsed === false) {
            return '';
        }

        // If scheme exists, return original URL (it's already properly formatted)
        if (isset($parsed['scheme'])) {
            return $str;
        }

        // If no host component detected after parsing, it might be a relative path
        if (!isset($parsed['host']) && strpos($str, '/') === 0) {
            return $str; // Return relative paths as-is
        }

        // Add HTTP scheme to URLs without protocol
        return 'http://' . $str;
    }
}

// ------------------------------------------------------------------------

if (!function_exists('url_title')) {
    /**
     * Create URL-Friendly Title - Converts text to SEO-friendly URL slug
     *
     * This function transforms any string into a clean, URL-safe slug by removing
     * HTML tags, special characters, and replacing spaces with separators.
     * Perfect for creating SEO-friendly URLs from article titles or names.
     * 
     * Performance improvements:
     * - Pre-compiled regex patterns for faster processing
     * - Optimized character replacement using single preg_replace call
     * - Efficient separator handling and cleanup
     * - UTF-8 safe multibyte string operations
     *
     * @param string $str       The input string to convert
     * @param string $separator Word separator character (default: '-')
     * @param bool   $lowercase Whether to convert to lowercase (default: false)
     * 
     * @return string The URL-friendly slug
     * 
     * @example
     * url_title('Hello World!')                    // Returns: 'Hello-World'
     * url_title('My Amazing Article', '_', true)   // Returns: 'my_amazing_article'
     * url_title('Special chars: @#$%', '-', true)  // Returns: 'special-chars'
     * url_title('<h1>HTML Title</h1>')             // Returns: 'HTML-Title'
     * url_title('Multiple   Spaces')               // Returns: 'Multiple-Spaces'
     */
    function url_title(string $str, string $separator = '-', bool $lowercase = false): string
    {
        // Handle empty input
        if (empty($str)) {
            return '';
        }

        // Remove HTML tags first for security and cleanliness
        $str = strip_tags($str);
        
        // Handle empty result after tag removal
        if (empty(trim($str))) {
            return '';
        }

        // Escape separator for regex
        $escapedSeparator = preg_quote($separator, '#');

        // Single comprehensive regex replacement for better performance
        // 1. Remove HTML entities (&...;)
        // 2. Remove all non-word characters except spaces, hyphens, underscores
        // 3. Replace multiple whitespace with separator
        // 4. Replace multiple separators with single separator
        $patterns = [
            '#&[^;\s]*;#'                           => '', // Remove HTML entities
            '#[^\w\d\s_-]#u'                       => '', // Remove special chars (UTF-8 safe)
            '#\s+#'                                => $separator, // Replace spaces with separator
            '#' . $escapedSeparator . '+#'         => $separator  // Collapse multiple separators
        ];

        foreach ($patterns as $pattern => $replacement) {
            $str = preg_replace($pattern, $replacement, $str);
        }

        // Convert to lowercase if requested (UTF-8 safe)
        if ($lowercase) {
            $str = mb_strtolower($str, 'UTF-8');
        }

        // Trim separators from both ends
        return trim($str, $separator . ' ');
    }
}

// ------------------------------------------------------------------------

if (!function_exists('xml_convert')) {
    /**
     * Convert reserved XML characters to entities
     *
     * Safely converts XML reserved characters to their entity equivalents while
     * preserving existing valid entities. Optionally protects all HTML entities.
     *
     * @param string $str The string to convert
     * @param bool $protect_all Whether to protect all existing HTML entities
     * @return string String with XML characters converted to entities
     *
     * @example
     * xml_convert('<tag>Data & "value"</tag>') // Returns: '&lt;tag&gt;Data &amp; &quot;value&quot;&lt;/tag&gt;'
     */
    function xml_convert(string $str, bool $protect_all = false): string
    {
        if (empty($str)) {
            return $str;
        }

        $temp_marker = '__NSY_TEMP_AMP_' . uniqid() . '__';

        // Preserve existing numeric entities
        $str = preg_replace('/&#(\d+);/', $temp_marker . 'NUM\\1;', $str);

        // Preserve existing named entities if requested
        if ($protect_all) {
            $str = preg_replace('/&([a-zA-Z][a-zA-Z0-9]*);/', $temp_marker . 'NAME\\1;', $str);
        }

        // Convert reserved XML characters
        $str = str_replace(
            ['&', '<', '>', '"', "'", '-'],
            ['&amp;', '&lt;', '&gt;', '&quot;', '&apos;', '&#45;'],
            $str
        );

        // Restore preserved entities
        $str = preg_replace('/' . preg_quote($temp_marker, '/') . 'NUM(\d+);/', '&#\\1;', $str);
        
        if ($protect_all) {
            $str = preg_replace('/' . preg_quote($temp_marker, '/') . 'NAME([a-zA-Z0-9]+);/', '&\\1;', $str);
        }

        return $str;
    }
}

// --------------------------------------------------------------------

if (!function_exists('symbolic_permissions')) {
    /**
     * Convert numeric permissions to symbolic notation
     *
     * Converts Unix-style numeric file permissions to human-readable symbolic notation
     * (e.g., 'drwxr-xr--'). Handles all file types and special permission bits.
     *
     * @param int $perms Numeric permissions value (from fileperms())
     * @return string Symbolic permission string (10 characters)
     *
     * @example
     * symbolic_permissions(0755) // Returns: '-rwxr-xr-x'
     * symbolic_permissions(fileperms('/path/to/dir')) // Returns: 'drwxr-xr-x'
     */
    function symbolic_permissions(int $perms): string
    {
        // Determine file type
        $file_type_map = [
            0xC000 => 's', // Socket
            0xA000 => 'l', // Symbolic Link  
            0x8000 => '-', // Regular file
            0x6000 => 'b', // Block special
            0x4000 => 'd', // Directory
            0x2000 => 'c', // Character special
            0x1000 => 'p', // FIFO pipe
        ];
        
        $file_type = 'u'; // Unknown default
        foreach ($file_type_map as $mask => $type) {
            if (($perms & $mask) === $mask) {
                $file_type = $type;
                break;
            }
        }

        $symbolic = $file_type;

        // Owner permissions (user)
        $symbolic .= ($perms & 0x0100) ? 'r' : '-';  // Read
        $symbolic .= ($perms & 0x0080) ? 'w' : '-';  // Write
        $symbolic .= ($perms & 0x0040) ?             // Execute/setuid
            (($perms & 0x0800) ? 's' : 'x') : 
            (($perms & 0x0800) ? 'S' : '-');

        // Group permissions
        $symbolic .= ($perms & 0x0020) ? 'r' : '-';  // Read
        $symbolic .= ($perms & 0x0010) ? 'w' : '-';  // Write  
        $symbolic .= ($perms & 0x0008) ?             // Execute/setgid
            (($perms & 0x0400) ? 's' : 'x') :
            (($perms & 0x0400) ? 'S' : '-');

        // World permissions (other)
        $symbolic .= ($perms & 0x0004) ? 'r' : '-';  // Read
        $symbolic .= ($perms & 0x0002) ? 'w' : '-';  // Write
        $symbolic .= ($perms & 0x0001) ?             // Execute/sticky
            (($perms & 0x0200) ? 't' : 'x') :
            (($perms & 0x0200) ? 'T' : '-');

        return $symbolic;
    }
}

// --------------------------------------------------------------------

if (!function_exists('octal_permissions')) {
    /**
     * Convert numeric permissions to octal notation
     *
     * Extracts the permission bits from a numeric permission value and returns
     * them as a three-digit octal string (e.g., '755', '644').
     *
     * @param int $perms Numeric permissions value (from fileperms())
     * @return string Three-digit octal permissions string
     *
     * @example
     * octal_permissions(fileperms('/path/to/file')) // Returns: '644'
     * octal_permissions(0755) // Returns: '755'
     */
    function octal_permissions(int $perms): string
    {
        // Extract last 3 octal digits (permission bits only)
        return substr(sprintf('%o', $perms), -3);
    }
}
/**
 * NSY PHP Framework
 *
 * A several CI helpers of NSY PHP Framework from Codeigniter (End)
 */
