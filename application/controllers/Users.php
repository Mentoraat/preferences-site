<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	/**
	 * Login with a user. If already logged in, redirects to provided
	 * $class/$method page.
	 *
	 * @param  string $class  The controller name of the page to redirect to.
	 * @param  string $method The method name of the page to redirect to.
	 * @return void
	 */
	public function login($class = 'main', $method = 'index')
	{
		if ($this->user->isLoggedIn())
		{
			return redirect($class . '/' . $method);
		}

		$data = array(
			'class' => $class,
			'method' => $method
		);

		$this->load->page('users/login', $data);
	}

	/**
	 * Try to login with specified post data.
	 *
	 * @param  string $class  The controller name of the page to redirect to.
	 * @param  string $method The method name of the page to redirect to.
	 * @return void
	 */
	public function tryLogin($class, $method)
	{
		$this->form_validation->set_rules(
			'netid',
			'Net ID',
			array(
				'required',
				array(
					'alreadyRegistered',
					array($this->user, 'alreadyRegistered')
				)
			),
			array(
				'alreadyRegistered' => 'User not registered.'
			)
		);

		$this->form_validation->set_rules(
			'password',
			'Password',
			array(
				'trim',
				'required',
				array(
					'passwordMatches',
					array($this->user, 'passwordMatches')
				)
			),
			array(
				'passwordMatches' => 'Invalid credentials.'
			)
		);

		if ($this->form_validation->run() == FALSE) {
			$data = array(
				'class' => $class,
				'method' => $method
			);

			$this->load->page('users/login', $data);
		}
		else {
			$netid = $this->input->post('netid');

			$this->user->setUser($netid);

			return redirect($class . '/' . $method);
		}
	}

	/**
	 * Load the register page.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->load->page('users/register');
	}

	/**
	 * Try to register the user with the specified post data.
	 *
	 * @return redirect Redirect to main page if succesful, else $this->register.
	 */
	public function tryRegister()
	{
		$this->form_validation->set_rules(
			'netid',
			'Net ID',
			array(
				'required',
				array(
					'existsByNetid',
					array($this->student, 'existsByNetid')
				),
				array(
					'notAlreadyRegistered',
					array($this->user, 'notAlreadyRegistered')
				)
			),
			array(
				'existsByNetid' => 'Net ID does not exist.',
				'notAlreadyRegistered' => 'This Net ID is already registered. If this is not you, please contact an administrator.'
			)
		);

		$this->form_validation->set_rules(
			'password',
			'Password',
			'trim|required'
		);

		$this->form_validation->set_rules(
			'passconf',
			'Password Confirmation',
			'trim|required|matches[password]'
		);

		$this->form_validation->set_rules(
			'studentid',
			'Student ID',
			array(
				'required',
				array(
					'matchesNetId',
					array($this->student, 'isSameStudent')
				)
			),
			array(
				'matchesNetId' => 'The Student ID and Net ID are not of the same student.'
			)
		);

		if ($this->form_validation->run() == FALSE) {
			$this->register();
		}
		else {
			$password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
			$netid = $this->input->post('netid');

			$this->user->register($netid, $password);

			return redirect('');
		}
	}

	/**
	 * Log out the current user.
	 * @return redirect Redirect to main page.
	 */
	public function logout()
	{
		$this->user->clear();

		return redirect('');
	}

}
