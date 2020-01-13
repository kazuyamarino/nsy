<?php
namespace System\Libraries\Exception;

/**
 * Attention, don't try to change the structure of the code, delete, or change.
 * Because there is some code connected to the NSY system. So, be careful.
 *
 * PHP simple library for managing Json files.
 *
 * @author    Josantonius <hello@josantonius.com>
 * @copyright 2016 - 2018 (c) Josantonius - PHP-Json
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/Josantonius/PHP-Json
 * @since     1.0.0
 */

/**
 * Exception class for Json library.
 *
 * You can use an exception and error handler with this library.
 *
 * @link https://github.com/Josantonius/PHP-ErrorHandler
 */
class JsonException extends \Exception
{
    /**
     * Exception handler.
     *
     * @param string $msg    → message error (Optional)
     * @param int    $status → HTTP response status code (Optional)
     */
    public function __construct($msg = '', $status = 0)
    {
        $this->message = $msg;
        $this->statusCode = $status;
    }
}
