<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	/**
	 * Load the main page.
	 *
	 * @return void
	 */
	public function index()
	{
		$this->load->page('main/index');
	}
}
