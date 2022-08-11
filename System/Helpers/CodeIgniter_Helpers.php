<?php
/**
 * NSY PHP Framework
 *
 * A several CI helpers of NSY PHP Framework from Codeigniter (Start)
 */
/**
 * stringify_attributes function
 *
 * @return string
 */
function stringify_attributes($attributes, $js = false)
{
    if (is_object($attributes) && count($attributes) > 0) {
        $attributes = (array) $attributes;
    }

    if (is_array($attributes)) {
        $atts = '';
        if (count($attributes) === 0) {
            return $atts;
        }
        foreach ($attributes as $key => $val)
        {
            if ($js) {
                $atts .= $key.'='.$val.',';
            }
            else
            {
                $atts .= ' '.$key.'="'.$val.'"';
            }
        }
        return rtrim($atts, ',');
    }
    elseif (is_string($attributes) && strlen($attributes) > 0) {
        return ' '.$attributes;
    }

    return $attributes;
}

// ------------------------------------------------------------------------

if (! function_exists('set_realpath')) {
    /**
     * Set Realpath
     *
     * @param  string
     * @param  bool    checks to see if the path exists
     * @return string
     */
    function set_realpath($path, $check_existance = false)
    {
        // Security check to make sure the path is NOT a URL. No remote file inclusion!
        if (preg_match('#^(http:\/\/|https:\/\/|www\.|ftp|php:\/\/)#i', $path) OR filter_var($path, FILTER_VALIDATE_IP) === $path) {
            echo 'The path you submitted must be a local server path, not a URL';
            exit();
        }

        // Resolve the path
        if (realpath($path) !== false) {
            $path = realpath($path);
        }
        elseif ($check_existance && ! is_dir($path) && ! is_file($path)) {
            echo 'Not a valid path: '.$path;
            exit();
        }

        // Add a trailing slash, if this is a directory
        return is_dir($path) ? rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR : $path;
    }
}

// ------------------------------------------------------------------------

if (! function_exists('random_element')) {
    /**
     * Random Element - Takes an array as input and returns a random element
     *
     * @param  array
     * @return mixed    depends on what the array contains
     */
    function random_element($array)
    {
        return is_array($array) ? $array[array_rand($array)] : $array;
    }
}

// --------------------------------------------------------------------

if (! function_exists('directory_map')) {
    /**
     * Create a Directory Map
     *
     * Reads the specified directory and builds an array
     * representation of it. Sub-folders contained with the
     * directory will be mapped as well.
     *
     * @param  string $source_dir      Path to source
     * @param  int    $directory_depth Depth of directories to traverse
     * @param  bool   $hidden          Whether to show hidden files
     * @return mixed
     */
    function directory_map($source_dir, $directory_depth = 0, $hidden = false)
    {
        if ($fp = @opendir($source_dir)) {
            $filedata    = array();
            $new_depth    = $directory_depth - 1;
            $source_dir    = rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

            while (false !== ($file = readdir($fp)))
            {
                // Remove '.', '..', and hidden files [optional]
                if ($file === '.' OR $file === '..' OR ($hidden === false && $file[0] === '.')) {
                    continue;
                }

                is_dir($source_dir.$file) && $file .= DIRECTORY_SEPARATOR;

                if (($directory_depth < 1 OR $new_depth > 0) && is_dir($source_dir.$file)) {
                    $filedata[$file] = directory_map($source_dir.$file, $new_depth, $hidden);
                }
                else
                {
                    $filedata[] = $file;
                }
            }

            closedir($fp);
            return $filedata;
        }

        return false;
    }
}

//--------------------------------------------------------------------

if (! function_exists('encode_php_tags')) {
    /**
     * Convert PHP tags to entities
     *
     * @param  string $str
     * @return string
     */
    function encode_php_tags(string $str): string
    {
        return str_replace(['<?', '?>'], ['&lt;?', '?&gt;'], $str);
    }
}

//--------------------------------------------------------------------

if (! function_exists('word_limiter')) {
    /**
     * Word Limiter
     *
     * Limits a string to X number of words.
     *
     * @param string  $str
     * @param integer $limit
     * @param string  $end_char the end character. Usually an ellipsis
     *
     * @return string
     */
    function word_limiter(string $str, int $limit = 100, string $end_char = '&#8230;'): string
    {
        if (trim($str) === '') {
            return $str;
        }

        preg_match('/^\s*+(?:\S++\s*+){1,' . (int) $limit . '}/', $str, $matches);

        if (strlen($str) === strlen($matches[0])) {
            $end_char = '';
        }

        return rtrim($matches[0]) . $end_char;
    }
}

//--------------------------------------------------------------------

if (! function_exists('character_limiter')) {
    /**
     * Character Limiter
     *
     * Limits the string based on the character count.  Preserves complete words
     * so the character count may not be exactly as specified.
     *
     * @param string  $str
     * @param integer $n
     * @param string  $end_char the end character. Usually an ellipsis
     *
     * @return string
     */
    function character_limiter(string $str, int $n = 500, string $end_char = '&#8230;'): string
    {
        if (mb_strlen($str) < $n) {
            return $str;
        }

        // a bit complicated, but faster than preg_replace with \s+
        $str = preg_replace('/ {2,}/', ' ', str_replace(["\r", "\n", "\t", "\x0B", "\x0C"], ' ', $str));

        if (mb_strlen($str) <= $n) {
            return $str;
        }

        $out = '';

        foreach (explode(' ', trim($str)) as $val)
        {
            $out .= $val . ' ';
            if (mb_strlen($out) >= $n) {
                $out = trim($out);
                break;
            }
        }
        return (mb_strlen($out) === mb_strlen($str)) ? $out : $out . $end_char;
    }
}

//--------------------------------------------------------------------

if (! function_exists('ascii_to_entities')) {
    /**
     * High ASCII to Entities
     *
     * Converts high ASCII text and MS Word special characters to character entities.
     *
     * @param string $str
     *
     * @return string
     */
    function ascii_to_entities(string $str): string
    {
        $out = '';

        for ($i = 0, $s = strlen($str) - 1, $count = 1, $temp = []; $i <= $s; $i ++)
        {
            $ordinal = ord($str[$i]);

            if ($ordinal < 128) {
                /*
                  If the $temp array has a value but we have moved on, then it seems only
                  fair that we output that entity and restart $temp before continuing.
                 */
                if (count($temp) === 1) {
                    $out  .= '&#' . array_shift($temp) . ';';
                    $count = 1;
                }

                $out .= $str[$i];
            }
            else
            {
                if (empty($temp)) {
                    $count = ($ordinal < 224) ? 2 : 3;
                }

                $temp[] = $ordinal;

                if (count($temp) === $count) {
                    $number = ($count === 3) ? (($temp[0] % 16) * 4096) + (($temp[1] % 64) * 64) + ($temp[2] % 64) : (($temp[0] % 32) * 64) + ($temp[1] % 64);
                    $out   .= '&#' . $number . ';';
                    $count  = 1;
                    $temp   = [];
                }
                // If this is the last iteration, just output whatever we have
                elseif ($i === $s) {
                    $out .= '&#' . implode(';', $temp) . ';';
                }
            }
        }

        return $out;
    }
}

//--------------------------------------------------------------------

if (! function_exists('entities_to_ascii')) {
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
            for ($i = 0, $s = count($matches[0]); $i < $s; $i ++)
            {
                $digits = $matches[1][$i];
                $out    = '';
                if ($digits < 128) {
                    $out .= chr($digits);
                }
                elseif ($digits < 2048) {
                    $out .= chr(192 + (($digits - ($digits % 64)) / 64)) . chr(128 + ($digits % 64));
                }
                else
                {
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
                ], [
                '&',
                '<',
                '>',
                '"',
                "'",
                '-',
                ], $str
            );
        }

        return $str;
    }
}

//--------------------------------------------------------------------

if (! function_exists('word_censor')) {
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

        foreach ($censored as $badword)
        {
            $badword = str_replace('\*', '\w*?', preg_quote($badword, '/'));

            if ($replacement !== '') {
                $str = preg_replace(
                    "/({$delim})(" . $badword . ")({$delim})/i", "\\1{$replacement}\\3", $str
                );
            }
            elseif (preg_match_all("/{$delim}(" . $badword . "){$delim}/i", $str, $matches, PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE)) {
                $matches = $matches[1];

                for ($i = count($matches) - 1; $i >= 0; $i --)
                {
                    $length = strlen($matches[$i][0]);
                    $str    = substr_replace(
                        $str, str_repeat('#', $length), $matches[$i][1], $length
                    );
                }
            }
        }

        return trim($str);
    }
}

//--------------------------------------------------------------------

if (! function_exists('highlight_code')) {
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
            ], [
            '<',
            '>',
            'phptagopen',
            'phptagclose',
            'asptagopen',
            'asptagclose',
            'backslashtmp',
            'scriptclose',
            ], $str
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
            ], [
            '<span style="color: #$1">',
            "$1</span>\n</span>\n</code>",
            '',
            ], $str
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
            ], [
            '&lt;?',
            '?&gt;',
            '&lt;%',
            '%&gt;',
            '\\',
            '&lt;/script&gt;',
            ], $str
        );
    }
}

//--------------------------------------------------------------------

if (! function_exists('highlight_phrase')) {
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

if (! function_exists('word_wrap')) {
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
            for ($i = 0, $c = count($matches[0]); $i < $c; $i ++)
            {
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

        foreach (explode("\n", $str) as $line)
        {
            // Is the line within the allowed character count?
            // If so we'll join it to the output and continue
            if (mb_strlen($line) <= $charlim) {
                $output .= $line . "\n";
                continue;
            }

            $temp = '';

            while (mb_strlen($line) > $charlim)
            {
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
            }
            else
            {
                $output .= $line . "\n";
            }
        }

        // Put our markers back
        if (! empty($unwrap)) {
            foreach ($unwrap as $key => $val)
            {
                $output = str_replace('{{unwrapped' . $key . '}}', $val, $output);
            }
        }

        // remove any trailing newline
        $output = rtrim($output);

        return $output;
    }
}

//--------------------------------------------------------------------

if (! function_exists('ellipsize')) {
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
            $end = mb_substr($str, 0, -($max_length - mb_strlen($beg)));
        }
        else
        {
            $end = mb_substr($str, -($max_length - mb_strlen($beg)));
        }

        return $beg . $ellipsis . $end;
    }
}

//--------------------------------------------------------------------

if (! function_exists('strip_slashes')) {
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
        if (! is_array($str)) {
            return stripslashes($str);
        }
        foreach ($str as $key => $val)
        {
            $str[$key] = strip_slashes($val);
        }

        return $str;
    }
}

//--------------------------------------------------------------------

if (! function_exists('strip_quotes')) {
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

if (! function_exists('quotes_to_entities')) {
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

if (! function_exists('reduce_double_slashes')) {
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

if (! function_exists('reduce_multiples')) {
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

if (! function_exists('random_string')) {
    /**
     * Create a Random String
     *
     * Useful for generating passwords or hashes.
     *
     * @param string  $type Type of random string.  basic, alpha, alnum, numeric, nozero, md5, sha1, and crypto
     * @param integer $len  Number of characters
     *
     * @return string
     */
    function random_string(string $type = 'alnum', int $len = 8): string
    {
        switch ($type)
        {
        case 'alnum':
        case 'numeric':
        case 'nozero':
        case 'alpha':
            switch ($type)
            {
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
            }

            return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
        case 'md5':
            return md5(uniqid(mt_rand(), true));
        case 'sha1':
            return sha1(uniqid(mt_rand(), true));
        case 'crypto':
            return bin2hex(random_bytes($len / 2));
        }
        // 'basic' type treated as default
        return (string) mt_rand();
    }
}

//--------------------------------------------------------------------

if (! function_exists('increment_string')) {
    /**
     * Add's _1 to a string or increment the ending number to allow _2, _3, etc
     *
     * @param string  $str       Required
     * @param string  $separator What should the duplicate number be appended with
     * @param integer $first     Which number should be used for the first dupe increment
     *
     * @return string
     */
    function increment_string(string $str, string $separator = '_', int $first = 1): string
    {
        preg_match('/(.+)' . preg_quote($separator, '/') . '([0-9]+)$/', $str, $match);

        return isset($match[2]) ? $match[1] . $separator . ($match[2] + 1) : $str . $separator . $first;
    }
}

//--------------------------------------------------------------------

if (! function_exists('alternator')) {
    /**
     * Alternator
     *
     * Allows strings to be alternated. See docs...
     *
     * @param string (as many parameters as needed)
     *
     * @return string
     */
    function alternator(): string
    {
        static $i;

        if (func_num_args() === 0) {
            $i = 0;

            return '';
        }

        $args = func_get_args();

        return $args[($i++ % count($args))];
    }
}

//--------------------------------------------------------------------

if (! function_exists('excerpt')) {
    /**
     * Excerpt.
     *
     * Allows to extract a piece of text surrounding a word or phrase.
     *
     * @param string  $text     String to search the phrase
     * @param string  $phrase   Phrase that will be searched for.
     * @param integer $radius   The amount of characters returned around the phrase.
     * @param string  $ellipsis Ending that will be appended
     *
     * @return string
     *
     * If no $phrase is passed, will generate an excerpt of $radius characters
     * from the beginning of $text.
     */
    function excerpt(string $text, string $phrase = null, int $radius = 100, string $ellipsis = '...'): string
    {
        if (isset($phrase)) {
            $phrasePos = strpos(strtolower($text), strtolower($phrase));
            $phraseLen = strlen($phrase);
        }
        elseif (! isset($phrase)) {
            $phrasePos = $radius / 2;
            $phraseLen = 1;
        }

        $pre = explode(' ', substr($text, 0, $phrasePos));
        $pos = explode(' ', substr($text, $phrasePos + $phraseLen));

        $prev  = ' ';
        $post  = ' ';
        $count = 0;

        foreach (array_reverse($pre) as $pr => $e)
        {
            if ((strlen($e) + $count + 1) < $radius) {
                $prev = ' ' . $e . $prev;
            }
            $count = ++ $count + strlen($e);
        }

        $count = 0;

        foreach ($pos as $po => $s)
        {
            if ((strlen($s) + $count + 1) < $radius) {
                $post .= $s . ' ';
            }
            $count = ++ $count + strlen($s);
        }

        $ellPre = $phrase ? $ellipsis : '';

        return str_replace('  ', ' ', $ellPre . $prev . $phrase . $post . $ellipsis);
    }
}

// ------------------------------------------------------------------------

if (! function_exists('prep_url')) {
    /**
     * Prep URL - Simply adds the http:// part if no scheme is included.
     *
     * Formerly used URI, but that does not play nicely with URIs missing
     * the scheme.
     *
     * @param  string $str the URL
     * @return string
     */
    function prep_url(string $str = ''): string
    {
        if ($str === 'http://' || $str === '') {
            return '';
        }

        $url = parse_url($str);

        if (! $url || ! isset($url['scheme'])) {
            return 'http://' . $str;
        }

        return $str;
    }
}

// ------------------------------------------------------------------------

if (! function_exists('url_title')) {
    /**
     * Create URL Title
     *
     * Takes a "title" string as input and creates a
     * human-friendly URL string with a "separator" string
     * as the word separator.
     *
     * @param  string  $str       Input string
     * @param  string  $separator Word separator (usually '-' or '_')
     * @param  boolean $lowercase Whether to transform the output string to lowercase
     * @return string
     */
    function url_title(string $str, string $separator = '-', bool $lowercase = false): string
    {
        $q_separator = preg_quote($separator, '#');

        $trans = [
        '&.+?;'                   => '',
        '[^\w\d _-]'              => '',
        '\s+'                     => $separator,
        '(' . $q_separator . ')+' => $separator,
        ];

        $str = strip_tags($str);
        foreach ($trans as $key => $val)
        {
            //            $str = preg_replace('#'.$key.'#i'.( UTF8_ENABLED ? 'u' : ''), $val, $str);
            $str = preg_replace('#' . $key . '#iu', $val, $str);
        }

        if ($lowercase === true) {
            $str = mb_strtolower($str);
        }

        return trim(trim($str, $separator));
    }
}

// ------------------------------------------------------------------------

if (! function_exists('xml_convert')) {
    /**
     * Convert Reserved XML characters to Entities
     *
     * @param  string
     * @param  bool
     * @return string
     */
    function xml_convert($str, $protect_all = false)
    {
        $temp = '__TEMP_AMPERSANDS__';

        // Replace entities to temporary markers so that
        // ampersands won't get messed up
        $str = preg_replace('/&#(\d+);/', $temp.'\\1;', $str);

        if ($protect_all === true) {
            $str = preg_replace('/&(\w+);/', $temp.'\\1;', $str);
        }

        $str = str_replace(
            array('&', '<', '>', '"', "'", '-'),
            array('&amp;', '&lt;', '&gt;', '&quot;', '&apos;', '&#45;'),
            $str
        );

        // Decode the temp markers back to entities
        $str = preg_replace('/'.$temp.'(\d+);/', '&#\\1;', $str);

        if ($protect_all === true) {
            return preg_replace('/'.$temp.'(\w+);/', '&\\1;', $str);
        }

        return $str;
    }
}

// --------------------------------------------------------------------

if (! function_exists('symbolic_permissions')) {
    /**
     * Symbolic Permissions
     *
     * Takes a numeric value representing a file's permissions and returns
     * standard symbolic notation representing that value
     *
     * @param  int $perms Permissions
     * @return string
     */
    function symbolic_permissions($perms)
    {
        if (($perms & 0xC000) === 0xC000) {
            $symbolic = 's'; // Socket
        }
        elseif (($perms & 0xA000) === 0xA000) {
            $symbolic = 'l'; // Symbolic Link
        }
        elseif (($perms & 0x8000) === 0x8000) {
            $symbolic = '-'; // Regular
        }
        elseif (($perms & 0x6000) === 0x6000) {
            $symbolic = 'b'; // Block special
        }
        elseif (($perms & 0x4000) === 0x4000) {
            $symbolic = 'd'; // Directory
        }
        elseif (($perms & 0x2000) === 0x2000) {
            $symbolic = 'c'; // Character special
        }
        elseif (($perms & 0x1000) === 0x1000) {
            $symbolic = 'p'; // FIFO pipe
        }
        else
        {
            $symbolic = 'u'; // Unknown
        }

        // Owner
        $symbolic .= (($perms & 0x0100) ? 'r' : '-')
        .(($perms & 0x0080) ? 'w' : '-')
        .(($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));

        // Group
        $symbolic .= (($perms & 0x0020) ? 'r' : '-')
        .(($perms & 0x0010) ? 'w' : '-')
        .(($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));

        // World
        $symbolic .= (($perms & 0x0004) ? 'r' : '-')
        .(($perms & 0x0002) ? 'w' : '-')
        .(($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));

        return $symbolic;
    }
}

// --------------------------------------------------------------------

if (! function_exists('octal_permissions')) {
    /**
     * Octal Permissions
     *
     * Takes a numeric value representing a file's permissions and returns
     * a three character string representing the file's octal permissions
     *
     * @param  int $perms Permissions
     * @return string
     */
    function octal_permissions($perms)
    {
        return substr(sprintf('%o', $perms), -3);
    }
}
/**
 * NSY PHP Framework
 *
 * A several CI helpers of NSY PHP Framework from Codeigniter (End)
 */
