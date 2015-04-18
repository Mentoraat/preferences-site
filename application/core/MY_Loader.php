<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_loader extends CI_Loader {

    /**
     * Load a full page with header and footer.
     *
     * @param  string $page The name of the view file to load.
     * @param  array  $vars The _ci_vars for $this->view() of CI_Controller.
     * @return void
     */
    public function page($page, $vars = array())
    {
        $this->view("header/index");
        $this->view($page, $vars);
        $this->view("footer/index");
    }
}
