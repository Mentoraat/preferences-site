<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authenticated_Controller extends CI_Controller {

    /**
	 * Check that the user is logged in before opening the specified page.
	 */
	public function __construct()
    {
		parent::__construct();

		if (!$this->user->isLoggedIn())
		{
			return $this->router->redirectBack('users/login');
		}
    }

}

class Admin_Controller extends Authenticated_Controller {

    /**
	 * Check that the user is admin in before opening the specified page.
	 */
	public function __construct()
    {
		parent::__construct();

		if (!$this->user->isAdmin())
		{
			return redirect('');
		}
    }
}

class AJAX_Controller extends CI_Controller {

	/**
	 * Check that the request is an AJAX request.
	 */
	public function __construct()
    {
		parent::__construct();

		if (!$this->input->is_ajax_request()) {
		   exit('No direct script access allowed');
		}
    }

	/**
	 * Show the AJAX response data on the screen.
	 *
	 * @param  array $data The array to show as JSON array.
	 * @return void
	 */
	private function show($data)
	{
		echo json_encode($data);

		die();
	}

	/**
	 * Let the current AJAX request fail.
	 *
	 * @return void
	 */
	protected function fail()
	{
		$this->show(array(
			'response' => 'failure'
		));
	}

	/**
	 * Show the response of the AJAX request on the screen with the response
	 * field on success.
	 *
	 * @param  array $data The data to show on the screen.
	 * @return void
	 */
	protected function succeed($data)
	{
		$data['response'] = 'success';
		$this->show($data);
	}
}
