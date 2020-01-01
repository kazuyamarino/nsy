<?php
defined('ROOT') OR exit('No direct script access allowed');

/**
 * NSY PHP Framework
 *
 * A several CI helpers of NSY PHP Framework from Codeigniter
 *
 * Attention, don't try to change the structure of the code, delete, or change.
 * Because there is some code connected to the NSY system. So, be careful.
 *
 * @package    NSY PHP Framework
 * @author     EllisLab Dev Team
 * @copyright  Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright  Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @license    https://opensource.org/licenses/MIT    MIT License
 * @link       https://nsy.kazuyamarino.com
 * @since      Version 1.0.0
 * @filesource
 */

//---------------------------------------------------------------------

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

/**
 * Returns the MIME types array from config/Mimes.php
 *
 * @return array
 */
function &get_mimes()
{
    static $_mimes;

    if (empty($_mimes)) {
        $_mimes = file_exists(__DIR__ . '/../config/Mimes.php')
        ? include __DIR__ . '/../config/Mimes.php'
        : array();

        if (file_exists(__DIR__ . '/../config/Mimes.php')) {
            $_mimes = array_merge($_mimes, include __DIR__ . '/../config/Mimes.php');
        }
    }

    return $_mimes;
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

if (! function_exists('element')) {
    /**
     * Element
     *
     * Lets you determine whether an array index is set and whether it has a value.
     * If the element is empty it returns NULL (or whatever you specify as the default value.)
     *
     * @param  string
     * @param  array
     * @param  mixed
     * @return mixed    depends on what the array contains
     */
    function element($item, array $array, $default = null)
    {
        return array_key_exists($item, $array) ? $array[$item] : $default;
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

if (! function_exists('elements')) {
    /**
     * Elements
     *
     * Returns only the array items specified. Will return a default value if
     * it is not set.
     *
     * @param  array
     * @param  array
     * @param  mixed
     * @return mixed    depends on what the array contains
     */
    function elements($items, array $array, $default = null)
    {
        $return = array();

        is_array($items) OR $items = array($items);

        foreach ($items as $item)
        {
            $return[$item] = array_key_exists($item, $array) ? $array[$item] : $default;
        }

        return $return;
    }
}

// ------------------------------------------------------------------------

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
     * @return array
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

// ------------------------------------------------------------------------

if (! function_exists('force_download')) {
    /**
     * Force Download
     *
     * Generates headers that force a download to happen
     *
     * @param  string    filename
     * @param  mixed    the data to be downloaded
     * @param  bool    whether to try and send the actual file MIME type
     * @return void
     */
    function force_download($filename = '', $data = '', $set_mime = false)
    {
        if ($filename === '' OR $data === '') {
            return;
        }
        elseif ($data === null) {
            if (! @is_file($filename) OR ($filesize = @filesize($filename)) === false) {
                return;
            }

            $filepath = $filename;
            $filename = explode('/', str_replace(DIRECTORY_SEPARATOR, '/', $filename));
            $filename = end($filename);
        }
        else
        {
            $filesize = strlen($data);
        }

        // Set the default MIME type to send
        $mime = 'application/octet-stream';

        $x = explode('.', $filename);
        $extension = end($x);

        if ($set_mime === true) {
            if (count($x) === 1 OR $extension === '') {
                /* If we're going to detect the MIME type,
                * we'll need a file extension.
                */
                return;
            }

            // Load the mime types
            $mimes =& get_mimes();

            // Only change the default MIME if we can find one
            if (isset($mimes[$extension])) {
                $mime = is_array($mimes[$extension]) ? $mimes[$extension][0] : $mimes[$extension];
            }
        }

        /* It was reported that browsers on Android 2.1 (and possibly older as well)
        * need to have the filename extension upper-cased in order to be able to
        * download it.
        *
        * Reference: http://digiblog.de/2011/04/19/android-and-the-download-file-headers/
        */
        if (count($x) !== 1 && isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Android\s(1|2\.[01])/', $_SERVER['HTTP_USER_AGENT'])) {
            $x[count($x) - 1] = strtoupper($extension);
            $filename = implode('.', $x);
        }

        if ($data === null && ($fp = @fopen($filepath, 'rb')) === false) {
            return;
        }

        // Clean output buffer
        if (ob_get_level() !== 0 && @ob_end_clean() === false) {
            @ob_clean();
        }

        // Generate the server headers
        header('Content-Type: '.$mime);
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Expires: 0');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: '.$filesize);
        header('Cache-Control: private, no-transform, no-store, must-revalidate');

        // If we have raw data - just dump it
        if ($data !== null) {
            exit($data);
        }

        // Flush 1MB chunks of data
        while ( ! feof($fp) && ($data = fread($fp, 1048576)) !== false)
        {
            echo $data;
        }

        fclose($fp);
        exit;
    }
}

//--------------------------------------------------------------------

if (! function_exists('strip_image_tags')) {
    /**
     * Strip Image Tags
     *
     * @param  string $str
     * @return string
     */
    function strip_image_tags(string $str): string
    {
        return preg_replace(
            [
            '#<img[\s/]+.*?src\s*=\s*(["\'])([^\\1]+?)\\1.*?\>#i',
            '#<img[\s/]+.*?src\s*=\s*?(([^\s"\'=<>`]+)).*?\>#i',
            ], '\\2', $str
        );
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
     * Converts high ASCII text and MS Word special characters to character entities
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
     * Converts character entities back to ASCII
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
     * Colorizes code strings
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
     * Highlights a phrase within a text string
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
     * This function will strip tags from a string, split it at its max_length and ellipsize
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
     * Removes slashes contained in a string or in an array
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
     * Removes single and double quotes from a string
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
     * Converts single and double quotes to entities
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

//--------------------------------------------------------------------

if (! function_exists('mailto')) {
    /**
     * Mailto Link
     *
     * @param string $email      the email address
     * @param string $title      the link title
     * @param mixed  $attributes any attributes
     *
     * @return string
     */
    function mailto(string $email, string $title = '', $attributes = ''): string
    {
        if (trim($title) === '') {
            $title = $email;
        }

        return '<a href="mailto:' . $email . '"' . stringify_attributes($attributes) . '>' . $title . '</a>';
    }
}

// ------------------------------------------------------------------------

if (! function_exists('safe_mailto')) {
    /**
     * Encoded Mailto Link
     *
     * Create a spam-protected mailto link written in Javascript
     *
     * @param string $email      the email address
     * @param string $title      the link title
     * @param mixed  $attributes any attributes
     *
     * @return string
     */
    function safe_mailto(string $email, string $title = '', $attributes = ''): string
    {
        if (trim($title) === '') {
            $title = $email;
        }

        $x = str_split('<a href="mailto:', 1);

        for ($i = 0, $l = strlen($email); $i < $l; $i ++)
        {
            $x[] = '|' . ord($email[$i]);
        }

        $x[] = '"';

        if ($attributes !== '') {
            if (is_array($attributes)) {
                foreach ($attributes as $key => $val)
                {
                    $x[] = ' ' . $key . '="';
                    for ($i = 0, $l = strlen($val); $i < $l; $i ++)
                    {
                        $x[] = '|' . ord($val[$i]);
                    }
                    $x[] = '"';
                }
            }
            else
            {
                for ($i = 0, $l = strlen($attributes); $i < $l; $i ++)
                {
                    $x[] = $attributes[$i];
                }
            }
        }

        $x[] = '>';

        $temp = [];
        for ($i = 0, $l = strlen($title); $i < $l; $i ++)
        {
            $ordinal = ord($title[$i]);

            if ($ordinal < 128) {
                $x[] = '|' . $ordinal;
            }
            else
            {
                if (empty($temp)) {
                    $count = ($ordinal < 224) ? 2 : 3;
                }

                $temp[] = $ordinal;
                if (count($temp) === $count) {
                    $number = ($count === 3) ? (($temp[0] % 16) * 4096) + (($temp[1] % 64) * 64) + ($temp[2] % 64) : (($temp[0] % 32) * 64) + ($temp[1] % 64);
                    $x[]    = '|' . $number;
                    $count  = 1;
                    $temp   = [];
                }
            }
        }

        $x[] = '<';
        $x[] = '/';
        $x[] = 'a';
        $x[] = '>';

        $x = array_reverse($x);

        // improve obfuscation by eliminating newlines & whitespace
        $output = '<script type="text/javascript">'
        . 'var l=new Array();';

        for ($i = 0, $c = count($x); $i < $c; $i ++)
        {
            $output .= 'l[' . $i . "] = '" . $x[$i] . "';";
        }

        $output .= 'for (var i = l.length-1; i >= 0; i=i-1) {'
        . "if (l[i].substring(0, 1) === '|') document.write(\"&#\"+unescape(l[i].substring(1))+\";\");"
        . 'else document.write(unescape(l[i]));'
        . '}'
        . '</script>';

        return $output;
    }
}

// ------------------------------------------------------------------------

if (! function_exists('auto_link')) {
    /**
     * Auto-linker
     *
     * Automatically links URL and Email addresses.
     * Note: There's a bit of extra code here to deal with
     * URLs or emails that end in a period. We'll strip these
     * off and add them after the link.
     *
     * @param string  $str   the string
     * @param string  $type  the type: email, url, or both
     * @param boolean $popup whether to create pop-up links
     *
     * @return string
     */
    function auto_link(string $str, string $type = 'both', bool $popup = false): string
    {
        // Find and replace any URLs.
        if ($type !== 'email' && preg_match_all('#(\w*://|www\.)[^\s()<>;]+\w#i', $str, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER)) {
            // Set our target HTML if using popup links.
            $target = ($popup) ? ' target="_blank"' : '';

            // We process the links in reverse order (last -> first) so that
            // the returned string offsets from preg_match_all() are not
            // moved as we add more HTML.
            foreach (array_reverse($matches) as $match)
            {
                // $match[0] is the matched string/link
                // $match[1] is either a protocol prefix or 'www.'
                //
                // With PREG_OFFSET_CAPTURE, both of the above is an array,
                // where the actual value is held in [0] and its offset at the [1] index.
                $a   = '<a href="' . (strpos($match[1][0], '/') ? '' : 'http://') . $match[0][0] . '"' . $target . '>' . $match[0][0] . '</a>';
                $str = substr_replace($str, $a, $match[0][1], strlen($match[0][0]));
            }
        }

        // Find and replace any emails.
        if ($type !== 'url' && preg_match_all('#([\w\.\-\+]+@[a-z0-9\-]+\.[a-z0-9\-\.]+[^[:punct:]\s])#i', $str, $matches, PREG_OFFSET_CAPTURE)) {
            foreach (array_reverse($matches[0]) as $match)
            {
                if (filter_var($match[0], FILTER_VALIDATE_EMAIL) !== false) {
                    $str = substr_replace($str, safe_mailto($match[0]), $match[1], strlen($match[0]));
                }
            }
        }

        return $str;
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

if (! function_exists('heading')) {
    /**
     * Heading
     *
     * Generates an HTML heading tag.
     *
     * @param  string    content
     * @param  int    heading level
     * @param  string
     * @return string
     */
    function heading($data = '', $h = '1', $attributes = '')
    {
        return '<h'.$h.stringify_attributes($attributes).'>'.$data.'</h'.$h.'>';
    }
}

// ------------------------------------------------------------------------

if (! function_exists('ul')) {
    /**
     * Unordered List
     *
     * Generates an HTML unordered list from an single or multi-dimensional array.
     *
     * @param  array
     * @param  mixed
     * @return string
     */
    function ul($list, $attributes = '')
    {
        return _list('ul', $list, $attributes);
    }
}

// ------------------------------------------------------------------------

if (! function_exists('ol')) {
    /**
     * Ordered List
     *
     * Generates an HTML ordered list from an single or multi-dimensional array.
     *
     * @param  array
     * @param  mixed
     * @return string
     */
    function ol($list, $attributes = '')
    {
        return _list('ol', $list, $attributes);
    }
}

// ------------------------------------------------------------------------

if (! function_exists('_list')) {
    /**
     * Generates the list
     *
     * Generates an HTML ordered list from an single or multi-dimensional array.
     *
     * @param  string
     * @param  mixed
     * @param  mixed
     * @param  int
     * @return string
     */
    function _list($type = 'ul', $list = array(), $attributes = '', $depth = 0)
    {
        // If an array wasn't submitted there's nothing to do...
        if (! is_array($list)) {
            return $list;
        }

        // Set the indentation based on the depth
        $out = str_repeat(' ', $depth)
        // Write the opening list tag
        .'<'.$type.stringify_attributes($attributes).">\n";


        // Cycle through the list elements.  If an array is
        // encountered we will recursively call _list()

        static $_last_list_item = '';
        foreach ($list as $key => $val)
        {
            $_last_list_item = $key;

            $out .= str_repeat(' ', $depth + 2).'<li>';

            if (! is_array($val)) {
                $out .= $val;
            }
            else
            {
                $out .= $_last_list_item."\n"._list($type, $val, '', $depth + 4).str_repeat(' ', $depth + 2);
            }

            $out .= "</li>\n";
        }

        // Set the indentation for the closing tag and apply it
        return $out.str_repeat(' ', $depth).'</'.$type.">\n";
    }
}

// ------------------------------------------------------------------------

if (! function_exists('img')) {
    /**
     * Image
     *
     * Generates an <img /> element
     *
     * @param  mixed
     * @param  bool
     * @param  mixed
     * @return string
     */
    function img($src = '', $attributes = '')
    {
        if (! is_array($src) ) {
            $src = array('src' => $src);
        }

        // If there is no alt attribute defined, set it to an empty string
        if (! isset($src['alt'])) {
            $src['alt'] = '';
        }

        $img = '<img';

        foreach ($src as $k => $v)
        {
            if ($k === 'src' && ! preg_match('#^(data:[a-z,;])|(([a-z]+:)?(?<!data:)//)#i', $v)) {
                $img .= ' src="'.base_url($v).'"';
            }
            else
            {
                $img .= ' '.$k.'="'.$v.'"';
            }
        }

        return $img.stringify_attributes($attributes).' />';
    }
}

// ------------------------------------------------------------------------

if (! function_exists('link_tag')) {
    /**
     * Link
     *
     * Generates link to a CSS file
     *
     * @param  mixed    stylesheet hrefs or an array
     * @param  string    rel
     * @param  string    type
     * @param  string    title
     * @param  string    media
     * @param  bool    should index_page be added to the css path
     * @return string
     */
    function link_tag($href = '', $rel = 'stylesheet', $type = 'text/css', $title = '', $media = '')
    {
        $CI =& get_instance();
        $link = '<link ';

        if (is_array($href)) {
            foreach ($href as $k => $v)
            {
                if ($k === 'href' && ! preg_match('#^([a-z]+:)?//#i', $v)) {
                    $link .= 'href="'.base_url($v).'" ';
                }
                else
                {
                    $link .= $k.'="'.$v.'" ';
                }
            }
        }
        else
        {
            if (preg_match('#^([a-z]+:)?//#i', $href)) {
                $link .= 'href="'.$href.'" ';
            }
            else
            {
                $link .= 'href="'.base_url($href).'" ';
            }

            $link .= 'rel="'.$rel.'" type="'.$type.'" ';

            if ($media !== '') {
                $link .= 'media="'.$media.'" ';
            }

            if ($title !== '') {
                $link .= 'title="'.$title.'" ';
            }
        }

        return $link."/>\n";
    }
}

// ------------------------------------------------------------------------

if (! function_exists('write_file')) {
    /**
     * Write File
     *
     * Writes data to the file specified in the path.
     * Creates a new file if non-existent.
     *
     * @param  string $path File path
     * @param  string $data Data to write
     * @param  string $mode fopen() mode (default: 'wb')
     * @return bool
     */
    function write_file($path, $data, $mode = 'wb')
    {
        if (! $fp = @fopen($path, $mode)) {
            return false;
        }

        flock($fp, LOCK_EX);

        for ($result = $written = 0, $length = strlen($data); $written < $length; $written += $result)
        {
            if (($result = fwrite($fp, substr($data, $written))) === false) {
                break;
            }
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        return is_int($result);
    }
}

// ------------------------------------------------------------------------

if (! function_exists('delete_files')) {
    /**
     * Delete Files
     *
     * Deletes all files contained in the supplied directory path.
     * Files must be writable or owned by the system in order to be deleted.
     * If the second parameter is set to TRUE, any directories contained
     * within the supplied base directory will be nuked as well.
     *
     * @param  string $path    File path
     * @param  bool   $del_dir Whether to delete any directories found in the path
     * @param  bool   $htdocs  Whether to skip deleting .htaccess and index page files
     * @param  int    $_level  Current directory depth level (default: 0; internal use only)
     * @return bool
     */
    function delete_files($path, $del_dir = false, $htdocs = false, $_level = 0)
    {
        // Trim the trailing slash
        $path = rtrim($path, '/\\');

        if (! $current_dir = @opendir($path)) {
            return false;
        }

        while (false !== ($filename = @readdir($current_dir)))
        {
            if ($filename !== '.' && $filename !== '..') {
                $filepath = $path.DIRECTORY_SEPARATOR.$filename;

                if (is_dir($filepath) && $filename[0] !== '.' && ! is_link($filepath)) {
                    delete_files($filepath, $del_dir, $htdocs, $_level + 1);
                }
                elseif ($htdocs !== true OR ! preg_match('/^(\.htaccess|index\.(html|htm|php)|web\.config)$/i', $filename)) {
                    @unlink($filepath);
                }
            }
        }

        closedir($current_dir);

        return ($del_dir === true && $_level > 0)
        ? @rmdir($path)
        : true;
    }
}

// ------------------------------------------------------------------------

if (! function_exists('get_filenames')) {
    /**
     * Get Filenames
     *
     * Reads the specified directory and builds an array containing the filenames.
     * Any sub-folders contained within the specified path are read as well.
     *
     * @param  string    path to source
     * @param  bool    whether to include the path as part of the filename
     * @param  bool    internal variable to determine recursion status - do not use in calls
     * @return array
     */
    function get_filenames($source_dir, $include_path = false, $_recursion = false)
    {
        static $_filedata = array();

        if ($fp = @opendir($source_dir)) {
            // reset the array and make sure $source_dir has a trailing slash on the initial call
            if ($_recursion === false) {
                $_filedata = array();
                $source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
            }

            while (false !== ($file = readdir($fp)))
            {
                if (is_dir($source_dir.$file) && $file[0] !== '.') {
                    get_filenames($source_dir.$file.DIRECTORY_SEPARATOR, $include_path, true);
                }
                elseif ($file[0] !== '.') {
                    $_filedata[] = ($include_path === true) ? $source_dir.$file : $file;
                }
            }

            closedir($fp);
            return $_filedata;
        }

        return false;
    }
}

// --------------------------------------------------------------------

if (! function_exists('get_dir_file_info')) {
    /**
     * Get Directory File Information
     *
     * Reads the specified directory and builds an array containing the filenames,
     * filesize, dates, and permissions
     *
     * Any sub-folders contained within the specified path are read as well.
     *
     * @param  string    path to source
     * @param  bool    Look only at the top level directory specified?
     * @param  bool    internal variable to determine recursion status - do not use in calls
     * @return array
     */
    function get_dir_file_info($source_dir, $top_level_only = true, $_recursion = false)
    {
        static $_filedata = array();
        $relative_path = $source_dir;

        if ($fp = @opendir($source_dir)) {
            // reset the array and make sure $source_dir has a trailing slash on the initial call
            if ($_recursion === false) {
                $_filedata = array();
                $source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
            }

            // Used to be foreach (scandir($source_dir, 1) as $file), but scandir() is simply not as fast
            while (false !== ($file = readdir($fp)))
            {
                if (is_dir($source_dir.$file) && $file[0] !== '.' && $top_level_only === false) {
                    get_dir_file_info($source_dir.$file.DIRECTORY_SEPARATOR, $top_level_only, true);
                }
                elseif ($file[0] !== '.') {
                    $_filedata[$file] = get_file_info($source_dir.$file);
                    $_filedata[$file]['relative_path'] = $relative_path;
                }
            }

            closedir($fp);
            return $_filedata;
        }

        return false;
    }
}

// --------------------------------------------------------------------

if (! function_exists('get_file_info')) {
    /**
     * Get File Info
     *
     * Given a file and path, returns the name, path, size, date modified
     * Second parameter allows you to explicitly declare what information you want returned
     * Options are: name, server_path, size, date, readable, writable, executable, fileperms
     * Returns FALSE if the file cannot be found.
     *
     * @param  string    path to file
     * @param  mixed    array or comma separated string of information returned
     * @return array
     */
    function get_file_info($file, $returned_values = array('name', 'server_path', 'size', 'date'))
    {
        if (! file_exists($file)) {
            return false;
        }

        if (is_string($returned_values)) {
            $returned_values = explode(',', $returned_values);
        }

        foreach ($returned_values as $key)
        {
            switch ($key)
            {
            case 'name':
                $fileinfo['name'] = basename($file);
                break;
            case 'server_path':
                $fileinfo['server_path'] = $file;
                break;
            case 'size':
                $fileinfo['size'] = filesize($file);
                break;
            case 'date':
                $fileinfo['date'] = filemtime($file);
                break;
            case 'readable':
                $fileinfo['readable'] = is_readable($file);
                break;
            case 'writable':
                $fileinfo['writable'] = is_really_writable($file);
                break;
            case 'executable':
                $fileinfo['executable'] = is_executable($file);
                break;
            case 'fileperms':
                $fileinfo['fileperms'] = fileperms($file);
                break;
            }
        }

        return $fileinfo;
    }
}

// --------------------------------------------------------------------

if (! function_exists('get_mime_by_extension')) {
    /**
     * Get Mime by Extension
     *
     * Translates a file extension into a mime type based on config/Mimes.php.
     * Returns FALSE if it can't determine the type, or open the mime config file
     *
     * Note: this is NOT an accurate way of determining file mime types, and is here strictly as a convenience
     * It should NOT be trusted, and should certainly NOT be used for security
     *
     * @param  string $filename File name
     * @return string
     */
    function get_mime_by_extension($filename)
    {
        static $mimes;

        if (! is_array($mimes)) {
            $mimes = get_mimes();

            if (empty($mimes)) {
                return false;
            }
        }

        $extension = strtolower(substr(strrchr($filename, '.'), 1));

        if (isset($mimes[$extension])) {
            return is_array($mimes[$extension])
            ? current($mimes[$extension]) // Multiple mime types, just give the first one
            : $mimes[$extension];
        }

        return false;
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
