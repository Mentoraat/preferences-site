<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_loader extends CI_Loader {

  public function import($view, $vars = array(), $return = FALSE)
  {
    if (!isset($vars["NOHEAD"]))
    {
      $vars["NOHEAD"] = 1;
    }

    return $this->view($view, $vars, $return);
  }

  public function page($page, $vars = array())
  {
    $this->view("header/index");
    $this->view($page, $vars);
    $this->view("footer/index");
  }
}
