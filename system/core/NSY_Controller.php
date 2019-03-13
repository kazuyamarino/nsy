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
    MVC View Folder
     */
    protected function load_view($filename) {
        extract($this->vars);
        require_once(MVC_VIEW_DIR . $filename . '.php');
		return $this;
    }

    /*
    HMVC View Folder
     */
    protected function load_hview($filename) {
        extract($this->vars);
        foreach (glob(HMVC_VIEW_DIR . $filename . '.php') as $results) {
            require_once($results);
        }
		return $this;
    }

    /*
    Template Directory
     */
    protected function load_template($filename) {
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

}
