<?php

namespace System\Core;

/**
 * Razr Template Engine
 */

use System\Core\Razr\Engine;
use System\Core\Razr\Loader\FilesystemLoader;

/**
 * This is the core of NSY Controller
 * Attention, don't try to change the structure of the code, delete, or change. Because there is some code connected to the NSY system. So, be careful.
 */
class Load
{

    /**
     * Shared Razr engine instance (lazy-initialized)
     *
     * @var Engine|null
     */
    static $razr;

    /**
     * Current HMVC module name (optional)
     *
     * @var string|null
     */
    static $module;

    /**
     * Render a view file from MVC or HMVC directory using the Razr engine
     *
     * If $module is empty, renders from MVC: get_mvc_view_dir(). Otherwise from HMVC: get_hmvc_view_dir().
     *
     * @param  string|null $module   HMVC module name or null/empty for MVC
     * @param  string      $filename View filename without extension
     * @param  array|object $vars    Variables passed to the view template
     * @return void
     */
    protected static function view(mixed $module = '', mixed $filename = '', array $vars = array())
    {
        // Validate inputs
        if (not_filled($filename) || !(is_array($vars) || is_object($vars))) {
            $var_msg = 'The variable in the <mark>Load::view()</mark> is improper or not an array';
            NSY_Desk::static_error_handler($var_msg);
            exit();
        }

        $razr = self::getRazr();
        $path = not_filled($module)
            ? get_mvc_view_dir() . $filename . '.php'
            : get_hmvc_view_dir() . $module . '/Views/' . $filename . '.php';

        echo $razr->render($path, $vars);
    }

    /**
     * Render a template from the system temporary directory using the Razr engine
     *
     * @param  string       $filename Template filename without extension
     * @param  array|object $vars     Variables passed to the template
     * @return void
     */
    protected static function template(mixed $filename = '', array $vars = array())
    {
        // Validate inputs
        if (not_filled($filename) || !(is_array($vars) || is_object($vars))) {
            $var_msg = 'The variable in the <mark>Load::template()</mark> is improper or not an array';
            NSY_Desk::static_error_handler($var_msg);
            exit();
        }

        $razr = self::getRazr();
        echo $razr->render(get_system_tmp_dir() . $filename . '.php', $vars);
    }

    /**
     * Instantiate a model by fully-qualified class name
     *
     * Modified by Vikry Yuansah for NSY System
     *
     * @param  string $fullclass Fully-qualified model class name
     * @return object            Instantiated model object
     */
    protected static function model(mixed $fullclass = '')
    {
        if (not_filled($fullclass) || !is_string($fullclass)) {
            $var_msg = 'The variable in the <mark>Load::model(<strong>model_name</strong>, <strong>method_name</strong>)</mark> is improper or not filled';
            NSY_Desk::static_error_handler($var_msg);
            exit();
        }

        if (!class_exists($fullclass)) {
            $var_msg = 'The model class <strong>' . htmlspecialchars($fullclass, ENT_QUOTES, 'UTF-8') . '</strong> was not found';
            NSY_Desk::static_error_handler($var_msg);
            exit();
        }

        return new $fullclass;
    }

    /**
     * Lazily initialize and reuse Razr Engine instance
     *
     * @return Engine Razr engine instance
     */
    private static function getRazr(): Engine
    {
        if (!self::$razr instanceof Engine) {
            self::$razr = new Engine(new FilesystemLoader(get_vendor_dir()));
        }
        return self::$razr;
    }
}
