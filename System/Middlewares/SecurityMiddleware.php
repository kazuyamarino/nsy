<?php

namespace System\Middlewares;

use Optimus\Onion\LayerInterface;

/**
 * Security Middleware for NSY Router
 * Provides CSRF protection, rate limiting, and input validation
 */
class SecurityMiddleware implements LayerInterface
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
     * Handle incoming request (Onion middleware compatible)
     */
    public function peel($object, \Closure $next)
    {
        // Rate limiting
        if (!$this->checkRateLimit()) {
            http_response_code(429);
            exit('Too Many Requests');
        }

        // CSRF Protection for POST/PUT/DELETE
        if ($this->config['csrf_protection'] && in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            if (!$this->validateCSRF()) {
                http_response_code(403);
                exit('CSRF Token Mismatch');
            }
        }

        // Input validation
        if ($this->config['validate_input']) {
            $this->validateInput();
        }

        return $next($object);
    }

    /**
     * Handle incoming request (legacy compatibility)
     */
    public function handle($request, $next)
    {
        return $this->peel($request, $next);
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
     * Validate CSRF token
     */
    private function validateCSRF()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        $sessionToken = $_SESSION['_csrf_token'] ?? '';

        return hash_equals($sessionToken, $token);
    }

    /**
     * Generate CSRF token
     */
    public static function generateCSRFToken()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (!isset($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf_token'];
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
    public static function csrfField()
    {
        $token = self::generateCSRFToken();
        return '<input type="hidden" name="_token" value="' . $token . '">';
    }

    /**
     * Get CSRF token meta tag
     */
    public static function csrfMeta()
    {
        $token = self::generateCSRFToken();
        return '<meta name="csrf-token" content="' . $token . '">';
    }
}
