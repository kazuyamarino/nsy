<?php
declare(strict_types=1);

/**
 * PHP library for handling requests.
 *
 * @author    Josantonius <hello@josantonius.com>
 * @copyright 2017 - 2018 (c) Josantonius - PHP-Request
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/Josantonius/PHP-Request
 * @since     1.0.0
 */
namespace System\Libraries;

use System\Libraries\Validate;

/**
 * Requests handler.
 */
class Request
{
    /**
     * Request params.
     *
     * @since 2.0.0
     *
     * @var array
     */
    protected static $input = [];

    /**
     * Request params.
     *
     * @since 2.0.0
     *
     * @var array
     */
    protected $params = [];

    /**
     * Request params.
     *
     * @since 2.0.0
     *
     * @var array
     */
    protected $key = null;

    /**
     * Get request params.
     *
     * @since 2.0.0
     *
     * @return anonymous function
     */
    public static function input(string $type)
    {
        $self = self::$input[$type] ?? new self;

        self::$input[$type] = $self;

        switch (strtoupper($type)) {
            case 'GET':
            case 'POST':
                $self->params = filter_input_array(constant('INPUT_' . $type)) ?? [];
                break;
            case 'PUT':
            case 'DELETE':
                $self->params = self::get_parsed_input();
                break;
        }

        return function ($key = null) use ($self) {
            $self->key = $key;

            return $self;
        };
    }

    /**
     * Data sanitation and return as array.
     *
     * @since 2.0.0
     *
     * @param array $filters → associative array with data type for each key
     * @param mixed $default → default value for non-existent or incorrect keys
     */
    public function as_array(array $filters = [], $default = null) : array
    {
        $array = Validate::as_array($this->params[$this->key] ?? $this->params, []);

        foreach ($filters as $key => $dataType) {
            $dataType = 'as_' . ucfirst($dataType);
            $array[$key] = Validate::$dataType($array[$key] ?? null, $default);
        }

        return $array;
    }

    /**
     * Data sanitation and return as object.
     *
     * @since 2.0.0
     *
     * @param array $filters → object with data type for each key
     * @param mixed $default → default value for non-existent or incorrect keys
     */
    public function as_object(array $filters = [], $default = null) : \stdClass
    {
        $object = Validate::as_object($this->params[$this->key] ?? $this->params, (object) []);

        foreach ($filters as $key => $dataType) {
            $dataType = 'as_' . ucfirst($dataType);
            $object->{$key} = Validate::$dataType($object->{$key} ?? null, $default);
        }

        return $object;
    }

    /**
     * Data sanitation and return as JSON.
     *
     * @since 2.0.0
     *
     * @param mixed $default → default value
     *
     * @return mixed → value, null or customized return value
     */
    public function as_json($default = null)
    {
        return Validate::as_json($this->params[$this->key] ?? $this->params, $default);
    }

    /**
     * Data sanitation and return as string.
     *
     * @since 2.0.0
     *
     * @param mixed $default → default value
     *
     * @return mixed → value, null or customized return value
     */
    public function as_string($default = null)
    {
        return Validate::as_string($this->params[$this->key] ?? null, $default);
    }

    /**
     * Data sanitation and return as integer number.
     *
     * @since 2.0.0
     *
     * @param mixed $default → default value
     *
     * @return mixed → value, null or customized return value
     */
    public function as_integer($default = null)
    {
        return Validate::as_integer($this->params[$this->key] ?? null, $default);
    }

    /**
     * Data sanitation and return as float number.
     *
     * @since 2.0.0
     *
     * @param mixed $default → default value
     *
     * @return mixed → value, null or customized return value
     */
    public function as_float($default = null)
    {
        return Validate::as_float($this->params[$this->key] ?? null, $default);
    }

    /**
     * Data sanitation and return as boolean.
     *
     * @since 2.0.0
     *
     * @param mixed $default → default value
     *
     * @return mixed → value, null or customized return value
     */
    public function as_boolean($default = null)
    {
        return Validate::as_boolean($this->params[$this->key] ?? [], $default);
    }

    /**
     * Data sanitation and return as IP.
     *
     * @since 2.0.0
     *
     * @param mixed $default → default value
     *
     * @return mixed → value, null or customized return value
     */
    public function as_ip($default = null)
    {
        return Validate::as_ip($this->params[$this->key] ?? null, $default);
    }

    /**
     * Data sanitation and return as URL.
     *
     * @since 2.0.0
     *
     * @param mixed $default → default value
     *
     * @return mixed → value, null or customized return value
     */
    public function as_url($default = null)
    {
        return Validate::as_url($this->params[$this->key] ?? null, $default);
    }

    /**
     * Data sanitation and return as email.
     *
     * @since 2.0.0
     *
     * @param mixed $default → default value
     *
     * @return mixed → value, null or customized return value
     */
    public function as_email($default = null)
    {
        return Validate::as_email($this->params[$this->key] ?? null, $default);
    }

    /**
     * Check if it is a GET request.
     */
    public static function is_get() : bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Check if it is a POST request.
     */
    public static function is_post() : bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Check if it is a PUT request.
     */
    public static function is_put() : bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'PUT';
    }

    /**
     * Check if it is a DELETE request.
     */
    public static function is_delete() : bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'DELETE';
    }

    /**
     * Get parsed input.
     *
     * @return mixed|null → value or null
     */
    public static function get_parsed_input()
    {
        $input = file_get_contents('php://input') ? : null;

        switch (self::get_content_type()) {
            case 'application/atom+xml':
                $input = new \SimpleXmlElement($input);
                break;
            case 'text/html':
            case 'text/plain':
            case 'application/json':
            case 'application/javascript':
                $input = json_decode($input, true);
                break;
            case 'multipart/form-data':
            case 'application/x-www-form-urlencoded':
                $data = [];
                self::parse_raw($input = '', $data);
                $input = $data;
                break;
        }

        return $input ?? [];
    }

    /**
     * Get request content type.
     *
     * @since 2.0.0
     */
    public static function get_content_type() : string
    {
        $contentType = $_SERVER['HTTP_CONTENT_TYPE'] ?? '';

        return explode(';', $contentType)[0];
    }

    /**
     * Parse raw http request to array.
     *
     * Based on parse_raw_http_request function of @Christof.
     *
     * @link https://stackoverflow.com/a/5488449
     *
     * @since 2.0.0
     */
    private static function parse_raw(string $input = '', array &$data = [])
    {
        preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);

        $blocks = preg_split('/-+' . ($matches[1] ?? '') . '/', $input);

        array_pop($blocks);

        foreach ($blocks as $id => $block) {
            if (empty($block)) {
                continue;
            }
            if (strpos($block, 'application/octet-stream') !== false) {
                preg_match("/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s", $block, $matches);
            } else {
                preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
            }
            $data[$matches[1]] = $matches[2];
        }
    }
}
