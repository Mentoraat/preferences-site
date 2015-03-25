<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Preferences extends CI_Controller {

	public function index()
	{
		$this->load->page('preferences/index');
	}
}
