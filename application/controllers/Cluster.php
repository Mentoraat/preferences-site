<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cluster extends Admin_Controller {

    /**
     * Shortcut to $this->output()
     *
     * @return $this->output
     */
    public function index()
    {
        $this->output();
    }

    /**
     * Output in matrix format the content of the preferences table.
     *
     * @return void
     */
    public function output()
    {
        $this->load->page('cluster/output');
    }

}
