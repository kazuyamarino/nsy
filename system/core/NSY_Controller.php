<?php
namespace Core;

defined('ROOT') OR exit('No direct script access allowed');

/*
 * Razr Template Engine
 */
use Razr\Engine;
use Razr\Loader\FilesystemLoader;

/*
 * This is the core of NSY Controller
 * 2018 - Vikry Yuansah
 * Attention, don't try to change the structure of the code, delete, or change. Because there is some code connected to the NSY system. So, be careful.
 */
class NSY_Controller {

    /**
     * HMVC & MVC View Folder
     */
    protected function load_view($module = null, $filename = null, $vars = array()) {
		if (is_array($vars) || is_object($vars))
		{
			// Instantiate Razr Template Engine
			$this->razr = new Engine(new FilesystemLoader(VENDOR_DIR));

			if( not_filled($module) ) {
				echo $this->razr->render(MVC_VIEW_DIR . $filename . '.php', $vars);
			} else {
				echo $this->razr->render(HMVC_VIEW_DIR . $module . '/views/' . $filename . '.php', $vars);
			}
		} else
		{
			exit('<p>The variable in the <strong>load_view()</strong> is improper or not an array</p>');
		}

		return $this;
    }

    /**
     * Template Directory
     */
    protected function load_template($filename = null, $vars = array()) {
		if (is_array($vars) || is_object($vars))
		{
			// Instantiate Razr Template Engine
			$this->razr = new Engine(new FilesystemLoader(VENDOR_DIR));

			echo $this->razr->render(SYS_TMP_DIR . $filename . '.php', $vars);
		} else
		{
			exit('<p>The variable in the <strong>load_tempate()</strong> is improper or not an array</p>');
		}

		return $this;
    }

	/**
	 * Start method for variables sequence
	 */
	protected function vars($variables = array()) {
 		$this->variables = $variables;

 		return $this;
 	}

	protected function bind($bind = null) {
		$this->bind = $bind;

		return $this;
	}

	/**
	 * Helper for NSY_Controller to create a sequence of the named placeholders
	 */
	protected function sequence() {
		$in = '';
		if (is_array($this->variables) || is_object($this->variables))
		{
			foreach ($this->variables as $i => $item)
			{
			    $key = $this->bind.$i;
			    $in .= $key.',';
			    $in_params[$key] = $item; // collecting values into key-value array
			}
		} else
		{
			exit('<p>Error message: The variable in the <strong>sequence()</strong> is improper or not an array</p>');
		}
		$in = rtrim($in,','); // example = :id0,:id1,:id2

		return [$in, $in_params];
	}

}
