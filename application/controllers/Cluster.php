<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cluster extends Admin_Controller {

    public function index()
    {
        $this->output();
    }

    public function output()
    {
        $this->load->page('cluster/output');
    }

}
