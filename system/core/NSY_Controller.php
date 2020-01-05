<?php
namespace System\Core;

defined('ROOT') OR exit('No direct script access allowed');

/**
 * Razr Template Engine
 */
use System\Razr\Engine;
use System\Razr\Loader\FilesystemLoader;

/**
 * Use NSY_Desk class
 */
use System\Core\NSY_Desk;

/**
 * This is the core of NSY Controller
 * Attention, don't try to change the structure of the code, delete, or change. Because there is some code connected to the NSY system. So, be careful.
 */
class NSY_Controller
{

    /**
     * HMVC & MVC View Folder
     *
     * @param  string $module
     * @param  string $filename
     * @param  array  $vars
     * @return string
     */
    protected function load_view($module = null, $filename = null, $vars = array())
    {
        // Instantiate Razr Template Engine
        $this->razr = new Engine(new FilesystemLoader(VENDOR_DIR));

        if (is_array($vars) || is_object($vars) ) {
            if(not_filled($module) ) {
                echo $this->razr->render(MVC_VIEW_DIR . $filename . '.php', $vars);
            } else {
                echo $this->razr->render(HMVC_VIEW_DIR . $module . '/views/' . $filename . '.php', $vars);
            }
        } else
        {
            $var_msg = 'The variable in the <mark>load_view()</mark> is improper or not an array';
            NSY_Desk::static_error_handler($var_msg);
            exit();
        }

        return $this;
    }

    /**
     * Template Directory
     *
     * @param  string $filename
     * @param  array  $vars
     * @return string
     */
    protected function load_template($filename = null, $vars = array())
    {
        // Instantiate Razr Template Engine
        $this->razr = new Engine(new FilesystemLoader(VENDOR_DIR));

        if (is_array($vars) || is_object($vars) ) {
            echo $this->razr->render(SYS_TMP_DIR . $filename . '.php', $vars);
        } else
        {
            $var_msg = 'The variable in the <mark>load_template()</mark> is improper or not an array';
            NSY_Desk::static_error_handler($var_msg);
            exit();
        }

        return $this;
    }

    /**
     * Method for variables sequence (vars)
     *
     * @param  array $variables
     * @return mixed
     */
    protected function vars($variables = array())
    {
        if (is_array($variables) || is_object($variables) ) {
            $this->variables = $variables;
        } else
        {
            $var_msg = 'The variable in the <mark>vars(<strong>variables</strong>)->sequence()</mark> is improper or not an array';
            NSY_Desk::static_error_handler($var_msg);
            exit();
        }

        return $this;
    }

    /**
     * Method for variables sequence (bind)
     *
     * @param  string $bind
     * @return string
     */
    protected function bind($bind = null)
    {
        if (is_filled($bind) ) {
            $this->bind = $bind;
        } else
        {
            $var_msg = 'The value that binds in the <mark>bind(<strong>value</strong>)->vars()->sequence()</mark> is empty or undefined';
            NSY_Desk::static_error_handler($var_msg);
            exit();
        }

        return $this;
    }

    /**
     * Helper for NSY_Controller to create a sequence of the named placeholders
     *
     * @return array
     */
    protected function sequence()
    {
        $in = '';
        if (is_array($this->variables) || is_object($this->variables) ) {
            foreach ($this->variables as $i => $item)
            {
                $key = $this->bind.$i;
                $in .= $key.',';
                $in_params[$key] = $item; // collecting values into key-value array
            }
        } else
        {
            $var_msg = 'The variable in the <mark>vars(<strong>variables</strong>)->sequence()</mark> is improper or not an array';
            NSY_Desk::static_error_handler($var_msg);
            exit();
        }
        $in = rtrim($in, ','); // example = :id0,:id1,:id2

        return [$in, $in_params];
    }

}
