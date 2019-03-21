<?php
namespace Core;

defined('ROOT') OR exit('No direct script access allowed');

class NSY_Controller {
    /*
    Define Variables as Array
     */
    var $vars = [];

    /*
    Set The Variables
     */
    protected function set($d) {
        $this->vars = array_merge($this->vars, $d);
    }

    /*
    HMVC & MVC View Folder
     */
    protected function load_view($module = null, $filename = null) {
        extract($this->vars);
		if( $module == "" || $module == null || empty($module) || !isset($module) ) {
			require_once(MVC_VIEW_DIR . $filename . '.php');
		} else {
			require_once(HMVC_VIEW_DIR . $module . '/views/' . $filename . '.php');
		}
		return $this;
    }

    /*
    Template Directory
     */
    protected function load_template($filename = null) {
		extract($this->vars);
        require_once(SYS_TMP_DIR . $filename . '.php');
		return $this;
    }

	/*
	Start method for variables sequence
	 */
	protected function vars($variables = "") {
 		$this->variables = $variables;
 		return $this;
 	}

	protected function bind($bind = "") {
		$this->bind = $bind;
		return $this;
	}

	// Helper for NSY_Model to create a sequence of the named placeholders
	protected function sequence() {
		$in = "";
		foreach ($this->variables as $i => $item)
		{
		    $key = "$this->bind".$i;
		    $in .= "$key,";
		    $in_params[$key] = $item; // collecting values into key-value array
		}
		$in = rtrim($in,","); // :id0,:id1,:id2

		return [$in, $in_params];
	}
	/*
	End method for variables sequence
	 */

	/*
	The PHP superglobals $_GET and $_POST are used to collect form-data.
	 */
	protected function post($param) {
		 $result = isset($_POST[$param]) ? $_POST[$param] : NULL;
		 return $result;
 	}

	protected function get($param) {
		 $result = isset($_GET[$param]) ? $_GET[$param] : NULL;
		 return $result;
 	}
	/*
	End The PHP superglobals $_GET and $_POST are used to collect form-data.
	 */

}
