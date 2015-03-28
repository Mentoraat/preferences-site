<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Preferences extends CI_Controller {

	public function __construct()
    {
		parent::__construct();

		if (!$this->user->isLoggedIn())
		{
			return $this->router->redirectBack('users/login');
		}
    }

	/**
	 * Default page that shows the possible preferences of a user.
	 *
	 * @return void
	 */
	public function index()
	{
		$data = array(
			'userid' => $this->user->getUserId()
		);

		$this->load->page('preferences/index', $data);
	}

	/**
	 * Updates the preferences of the current user to the preferences supplied
	 * in the POST fields.
	 * If succesful, it redirects back to the index page. Else it will notify
	 * the user that something went wrong.
	 *
	 * @return void
	 */
	public function update()
	{
		$this->form_validation->set_rules(
			'userid',
			'Net ID',
			array(
				'required',
				array(
					'exists_by_netid',
					array($this->student, 'exists_by_netid')
				)
			),
			'Net ID does not exist.'
		);

		$this->form_validation->set_rules(
			'names[]',
			'Names',
			array(
				'required',
				array(
					'exists_by_netid',
					array($this->student, 'exists_by_netid')
				)
			),
			'One of the names is not a valid student name.'
		);

		if ($this->form_validation->run() == FALSE) {
			$this->load->page('preferences/index');
		}
		else {
			return redirect('preferences/index/success');
		}
	}

}
