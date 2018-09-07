<?php

namespace Core;

defined('ROOT') OR exit('No direct script access allowed');

Class NSY_Controller {

  var $vars = [];

  public function set($d) {
    $this->vars = array_merge($this->vars, $d);
  }

  public function view($filename) {
    extract($this->vars);
    require_once(ROOT . 'Views' . '/' . $filename . '.php');
  }

  public function hmvc_view($filename) {
    extract($this->vars);
    foreach (glob(ROOT . 'Modules/*/Views' . '/' . $filename . '.php') as $results) {
        require_once($results);
    }
  }

  public function template($filename) {
    require_once(TEMPLATE_DIR . $filename . '.php');
  }

  private function secure_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  protected function secure_form($form) {
    foreach ($form as $key => $value) {
        $form[$key] = $this->secure_input($value);
    }
  }

}
