<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Preferences extends CI_Controller {

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
					'isCurrentUser',
					array($this->user, 'isCurrentUser')
				)
			),
			array(
				'isCurrentUser' => 'Net ID does not exist.'
			)
		);

		$seenNames = array();
		$length = NULL;

		$this->form_validation->set_rules(
			'names[]',
			'Names',
			array(
				array(
					'existsByNetid',
					array($this->student, 'existsByNetid')
				),
				array(
					'distinct',
					function ($name) use (&$seenNames)
					{
						if (in_array($name, $seenNames) || $this->user->isCurrentNetid($name))
						{
							return FALSE;
						}
						else
						{
							$seenNames[] = $name;
							return TRUE;
						}
					}
				),
				array(
					'length',
					function ($name) use (&$length)
					{
						if ($length === NULL)
						{
							$numberOfPreferences = count(array_filter($this->input->post('names')));
							$length = $numberOfPreferences >= MINIMUM_NUMBER_OF_PREFERENCES
								&& $numberOfPreferences <= MAXIMUM_NUMBER_OF_PREFERENCES;
						}

						return $length;
					}
				)
			),
			array(
				'existsByNetid' => 'One of the names is not a valid student name.',
				'distinct' => 'The provided students contain duplicate/incorrect values.',
				'length' => 'Provide at least ' . MINIMUM_NUMBER_OF_PREFERENCES . ' and at most ' . MAXIMUM_NUMBER_OF_PREFERENCES . ' students'
			)
		);

		if ($this->form_validation->run() === FALSE)
		{
			$this->load->page('preferences/index');
		}
		else
		{
			$this->preference->update($seenNames);

			return redirect('preferences/index/success');
		}
	}

}
