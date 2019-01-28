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
    public function hmvc_view($filename) {
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

}
