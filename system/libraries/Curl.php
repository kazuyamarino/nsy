<?php
namespace System\Libraries;

/**
 * Attention, don't try to change the structure of the code, delete, or change.
 * Because there is some code connected to the NSY system. So, be careful.
 *
 * API Requests using the HTTP protocol through the Curl library.
 *
 * @author    Josantonius <hello@josantonius.com>
 * @copyright 2016 - 2018 (c) Josantonius - PHP-Curl
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/Josantonius/PHP-Curl
 * @since     1.0.0
 */

/**
 * Curl handler.
 */
class Curl
{
    /**
     * Default params.
     *
     * @since 1.1.3
     *
     * @var string
     */
    protected static $defaultParams = [
        'data' => '',
        'type' => 'get',
        'referer' => null,
        'timeout' => 30,
        'headers' => ['Content-Type:text/html'],
        'agent' => 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0',
    ];

    /**
     * Make request and get response website.
     *
     * @param string $url    → url when get
     *                       content
     *
     * @param array  $params
     *                      string $params['referer'] → the referrer URL
     *                      int    $params['timeout'] → timeout
     *                      string $params['agent']   → useragent
     *                      array  $params['headers'] → HTTP headers
     *                      array  $params['data']    → parameters to send
     *                      string $params['type']    → type of request
     *
     * @param string $result → return result as array or object
     *
     * @throws CurlException → some request params wasn't received
     *
     * @return array|object → response
     */
    public static function request($url, $params = [], $result = 'array')
    {
        $init = curl_init($url);

        $params = self::check_params($params);

        if (! $curl = self::set_curl_options($params)) {
            return false;
        }

        curl_setopt_array($init, $curl);

        $output = curl_exec($init);

        curl_close($init);

        $sanitize = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $output);

        $response = json_decode($sanitize, $result === 'array');

        return ($response && json_last_error() === 0) ? $response : false;
    }

    /**
     * Check request params.
     *
     * @since 1.1.3
     *
     * @param string $params → request params
     *
     * @return array → request params
     */
    private static function check_params($params)
    {
        $values = ['data', 'type', 'referer', 'timeout', 'agent', 'headers'];

        foreach ($values as $value) {
            if (! isset($params[$value])) {
                $params[$value] = self::$defaultParams[$value];
            }
        }

        return $params;
    }

    /**
     * Set parameters according to the type of request.
     *
     * @since 1.1.3
     *
     * @param string $param → request params
     *
     * @return array → request params
     */
    private static function set_curl_options($param)
    {
        return in_array($param['type'], ['get', 'post', 'put', 'delete'], true) ? [
            CURLOPT_VERBOSE => true,
            CURLOPT_REFERER => $param['referer'] ?: self::get_url(),
            CURLOPT_TIMEOUT => $param['timeout'],
            CURLOPT_USERAGENT => $param['agent'],
            CURLOPT_HTTPHEADER => $param['headers'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => in_array($param['type'], ['post', 'put'], true),
            CURLOPT_POSTFIELDS => json_encode($param['data']) ?: null,
            CURLOPT_CUSTOMREQUEST => strtoupper($param['type']),
        ] : false;
    }

    /**
     * Check the protocol site (http | https) and get the current url.
     *
     * @return string → full url
     */
    private static function get_url()
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            return 'https://' . $_SERVER['HTTP_HOST'];
        }

        return 'http://' . $_SERVER['HTTP_HOST'];
    }
}
