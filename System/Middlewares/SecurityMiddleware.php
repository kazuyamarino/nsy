<?php

namespace System\Middlewares;


/**
 * Security Middleware for NSY Router
 * Provides CSRF protection, rate limiting, and input validation
 */
class SecurityMiddleware
{
    private $config;
    private static $requestCounts = [];

    public function __construct($config = [])
    {
        $this->config = array_merge([
            'csrf_protection' => true,
            'rate_limit' => 60, // requests per minute
            'rate_window' => 60, // seconds
            'validate_input' => true,
            'block_suspicious_patterns' => true
        ], $config);
    }


    /**
     * Check rate limiting
     */
    private function checkRateLimit()
    {
        $ip = $this->getClientIP();
        $now = time();
        $windowStart = $now - $this->config['rate_window'];

        // Clean old entries
        if (isset(self::$requestCounts[$ip])) {
            self::$requestCounts[$ip] = array_filter(
                self::$requestCounts[$ip],
                function($timestamp) use ($windowStart) {
                    return $timestamp > $windowStart;
                }
            );
        } else {
            self::$requestCounts[$ip] = [];
        }

        // Check if limit exceeded
        if (count(self::$requestCounts[$ip]) >= $this->config['rate_limit']) {
            return false;
        }

        // Add current request
        self::$requestCounts[$ip][] = $now;
        return true;
    }

    /**
     * Validate CSRF token with advanced features
     */
    private function validateCSRF()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        $key = '_csrf_token';

        // Support custom token keys from headers
        $customKey = $_SERVER['HTTP_X_CSRF_KEY'] ?? null;
        if ($customKey) {
            $key = 'csrf_' . $customKey;
        }

        return self::checkCSRFToken($key, $token);
    }

    /**
     * Check CSRF token with expiration and origin validation
     */
    public static function checkCSRFToken($key, $token, $throwException = false, $timeSpan = null, $multiple = false)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        // Check if session token exists
        if (!isset($_SESSION[$key])) {
            if ($throwException) {
                throw new \Exception('Missing CSRF session token');
            }
            return false;
        }

        $sessionToken = $_SESSION[$key];

        // Clear session token for one-time use (unless multiple is true)
        if (!$multiple) {
            $_SESSION[$key] = '';
        }

        // Basic token match
        if (!hash_equals($sessionToken, $token)) {
            if ($throwException) {
                throw new \Exception('Invalid CSRF token');
            }
            return false;
        }

        // Check token expiration if timeSpan is set
        if ($timeSpan !== null && self::isTokenExpired($sessionToken, $timeSpan)) {
            if ($throwException) {
                throw new \Exception('CSRF token has expired');
            }
            return false;
        }

        return true;
    }

    /**
     * Generate CSRF token with expiration and origin tracking
     */
    public static function generateCSRFToken($key = '_csrf_token', $enableOriginCheck = false)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $extra = '';
        if ($enableOriginCheck) {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
            $extra = hash('SHA256', $ip . $userAgent);
        }

        // Generate token with timestamp for expiration support
        $timestamp = time();
        $randomData = bin2hex(random_bytes(32));

        // Create token: base64(timestamp + extra + randomData)
        $tokenData = $timestamp . $extra . $randomData;
        $token = base64_encode($tokenData);

        $_SESSION[$key] = $token;

        return $token;
    }

    /**
     * Check if token has expired
     */
    private static function isTokenExpired($token, $timeSpan)
    {
        try {
            $decoded = base64_decode($token);
            $timestamp = substr($decoded, 0, 10);
            return (intval($timestamp) + $timeSpan) < time();
        } catch (Exception $e) {
            return true; // Invalid token format, consider expired
        }
    }

    /**
     * Generate CSRF token for specific key
     */
    public static function generateCSRFTokenForKey($key, $enableOriginCheck = false)
    {
        return self::generateCSRFToken('csrf_' . $key, $enableOriginCheck);
    }

    /**
     * Validate origin (IP + User Agent)
     */
    private static function validateOrigin($token)
    {
        try {
            $decoded = base64_decode($token);
            $storedHash = substr($decoded, 10, 64); // Extract stored hash

            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
            $currentHash = hash('SHA256', $ip . $userAgent);

            return hash_equals($storedHash, $currentHash);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Validate input for suspicious patterns
     */
    private function validateInput()
    {
        $suspiciousPatterns = [
            '/\<script.*?\>/i',
            '/javascript:/i',
            '/on\w+\s*=/i',
            '/\.\.\//i',
            '/union.*select/i',
            '/drop\s+table/i',
        ];

        $inputs = array_merge($_GET, $_POST, $_COOKIE);

        foreach ($inputs as $key => $value) {
            if (is_string($value)) {
                foreach ($suspiciousPatterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        http_response_code(400);
                        exit('Suspicious input detected');
                    }
                }
            }
        }
    }

    /**
     * Get real client IP
     */
    private function getClientIP()
    {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];

        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = explode(',', $ip)[0];
                }
                return trim($ip);
            }
        }

        return '127.0.0.1';
    }

    /**
     * Get CSRF token HTML input
     */
    public static function csrfField($key = '_csrf_token', $enableOriginCheck = false)
    {
        $token = self::generateCSRFToken($key, $enableOriginCheck);
        $fieldName = ($key === '_csrf_token') ? '_token' : str_replace('csrf_', '', $key);
        return '<input type="hidden" name="' . $fieldName . '" value="' . $token . '">';
    }

    /**
     * Get CSRF token meta tag
     */
    public static function csrfMeta($key = '_csrf_token', $enableOriginCheck = false)
    {
        $token = self::generateCSRFToken($key, $enableOriginCheck);
        return '<meta name="csrf-token" content="' . $token . '">';
    }

    /**
     * Validate CSRF token - Unified API method
     *
     * @param string|null $token Token to validate
     * @param string $key Token key/name
     * @param int|null $expiration Token expiration in seconds (null = no expiration)
     * @param bool $originCheck Whether to validate origin (IP + User Agent)
     * @return bool True if valid, false otherwise
     */
    public static function validateCSRFToken(?string $token, string $key = 'csrf_token', ?int $expiration = null, bool $originCheck = false)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (empty($token)) {
            return false;
        }

        // Normalize key format
        $sessionKey = (strpos($key, 'csrf_') === 0) ? $key : 'csrf_' . $key;

        return self::checkCSRFToken($sessionKey, $token, false, $expiration, false);
    }

    /**
     * Advanced CSRF validation with all the features
     */
    public static function validateAdvancedCSRF($key, $origin, $throwException = false, $timeSpan = null, $multiple = false, $enableOriginCheck = false)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        // Get token from form data
        $token = $origin[$key] ?? '';
        if (empty($token)) {
            if ($throwException) {
                throw new \Exception('Missing CSRF form token');
            }
            return false;
        }

        // Validate with enhanced features
        $sessionKey = (strpos($key, 'csrf_') === 0) ? $key : 'csrf_' . $key;

        if (!self::checkCSRFToken($sessionKey, $token, $throwException, $timeSpan, $multiple)) {
            return false;
        }

        // Additional origin validation if enabled
        if ($enableOriginCheck && !self::validateOrigin($token)) {
            if ($throwException) {
                throw new \Exception('Form origin does not match token origin');
            }
            return false;
        }

        return true;
    }

    /**
     * Create CSRF protection instance
     */
    public static function createCSRFProtection($enableOriginCheck = false)
    {
        return new class($enableOriginCheck) {
            private $enableOriginCheck;

            public function __construct($enableOriginCheck = false) {
                $this->enableOriginCheck = $enableOriginCheck;
            }

            public function enableOriginCheck() {
                $this->enableOriginCheck = true;
            }

            public function generate($key) {
                return SecurityMiddleware::generateCSRFTokenForKey($key, $this->enableOriginCheck);
            }

            public function check($key, $origin, $throwException = false, $timeSpan = null, $multiple = false) {
                return SecurityMiddleware::validateAdvancedCSRF($key, $origin, $throwException, $timeSpan, $multiple, $this->enableOriginCheck);
            }
        };
    }

    /**
     * Sanitize input data
     *
     * @param string $data Input data to sanitize
     * @return string Sanitized data
     */
    public static function sanitizeInput($data = '')
    {
        $data = trim($data ?? '');
        $data = stripslashes($data ?? '');
        $data = htmlspecialchars($data ?? '');

        return $data;
    }

    /**
     * Sanitize form data (array or object)
     *
     * @param mixed $form Form data to sanitize
     * @return mixed Sanitized form data
     */
    public static function sanitizeForm($form = '')
    {
        if (is_array($form) || is_object($form)) {
            foreach ($form as $key => $value) {
                if (is_string($value)) {
                    $form[$key] = self::sanitizeInput($value);
                } elseif (is_array($value) || is_object($value)) {
                    $form[$key] = self::sanitizeForm($value);
                }
            }
        } elseif (is_string($form)) {
            $form = self::sanitizeInput($form);
        }

        return $form;
    }

    /**
     * Clean XSS from input data
     *
     * @param string|array $data Data to clean from XSS
     * @return string|array Cleaned data
     */
    public static function cleanXSS($data)
    {
        if (!class_exists('voku\helper\AntiXSS')) {
            // Fallback if AntiXSS not available
            if (is_string($data)) {
                return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
            } elseif (is_array($data)) {
                return array_map([self::class, 'cleanXSS'], $data);
            }
            return $data;
        }

        $antiXSS = new \voku\helper\AntiXSS();
        
        if (is_string($data)) {
            return $antiXSS->xss_clean($data);
        } elseif (is_array($data)) {
            return array_map(function($item) use ($antiXSS) {
                return is_string($item) ? $antiXSS->xss_clean($item) : $item;
            }, $data);
        }

        return $data;
    }

    /**
     * Comprehensive input validation and sanitization
     *
     * @param mixed $data Data to validate and sanitize
     * @param array $options Validation options
     * @return mixed Validated and sanitized data
     */
    public static function validateAndSanitize($data, $options = [])
    {
        $defaults = [
            'trim' => true,
            'strip_slashes' => true,
            'html_escape' => true,
            'xss_clean' => false,
            'max_length' => null,
            'allowed_tags' => null
        ];

        $options = array_merge($defaults, $options);

        if (is_string($data)) {
            // Trim whitespace
            if ($options['trim']) {
                $data = trim($data);
            }

            // Strip slashes
            if ($options['strip_slashes']) {
                $data = stripslashes($data);
            }

            // Length validation
            if ($options['max_length'] && strlen($data) > $options['max_length']) {
                $data = substr($data, 0, $options['max_length']);
            }

            // XSS cleaning
            if ($options['xss_clean']) {
                $data = self::cleanXSS($data);
            }

            // HTML escaping
            if ($options['html_escape']) {
                if ($options['allowed_tags']) {
                    $data = strip_tags($data, $options['allowed_tags']);
                } else {
                    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
                }
            }
        } elseif (is_array($data) || is_object($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::validateAndSanitize($value, $options);
            }
        }

        return $data;
    }
}
