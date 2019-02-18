<?php

namespace System\Core;

use System\Core\NSY_DB;

defined('ROOT') OR exit('No direct script access allowed');

class NSY_Controller {
    /*
    Define Variables as Array
     */
    var $vars = [];

    /*
    Set The Variables
     */
    public function set($d) {
        $this->vars = array_merge($this->vars, $d);
    }

    /*
    MVC View Folder
     */
    public function view($filename) {
        extract($this->vars);
        require_once(MVC_VIEW_DIR . $filename . '.php');
    }

    /*
    HMVC View Folder
     */
    public function hview($filename) {
        extract($this->vars);
        foreach (glob(HMVC_VIEW_DIR . $filename . '.php') as $results) {
            require_once($results);
        }
    }

    /*
    Template Directory
     */
    public function template($filename) {
        require_once(SYS_TMP_DIR . $filename . '.php');
    }

    /*
    Secure Input Element
     */
    protected function secure_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    /*
    Secure Form
     */
    protected function secure_form($form) {
        foreach ($form as $key => $value) {
            $form[$key] = $this->secure_input($value);
        }
    }

	/*
    Redirect URL
     */
    public function redirect($url = NULL) {
		header("location:". BASE_URL . $url);
    }

	/*
	Helper for NSY_Model to create a sequence of the named placeholders
	 */
	public function varSeq($varname = "", $ids = "", $var = "", $param = "") {
		$in = "";
		foreach ($ids as $i => $item)
		{
		    $key = "$varname".$i;
		    $in .= "$key,";
		    $in_params[$key] = $item; // collecting values into key-value array
		}
		$in = rtrim($in,","); // :id0,:id1,:id2

		if ($var == "" || empty($var) || !isset($var) || $param == "" || empty($param) || !isset($param)) {
			return [$in, $in_params];
		} else {
			return [
				$var => $in,
				$param => $in_params
			];
		}
	}

}
