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
				),
				array(
					'failedTooMany',
					array($this->failedlogin, 'notFailedToMany')
				)
			),
			array(
				'alreadyRegistered' => 'User not registered.',
				'failedTooMany' => 'You have submitted invalid credentials too many times. Please contact an administrator.'
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

		$netid = $this->input->post('netid');

		if ($this->form_validation->run() == FALSE) {
			if ($this->user->alreadyRegistered($netid))
			{
				$this->failedlogin->increment($netid);
			}

			$data = array(
				'class' => $class,
				'method' => $method
			);

			$this->load->page('users/login', $data);
		}
		else {
			$this->user->setUser($netid);
			$this->failedlogin->reset($netid);

			return redirect($class . '/' . $method);
		}
	}

	/**
	 * Load the register page.
	 *
	 * @return void
	 */
	public function register($status = '')
	{
		$this->load->page('users/register', array('status' => $status));
	}

	/**
	 * Try to register the user with the specified post data.
	 *
	 * @return redirect Redirect to main page if succesful, else $this->register.
	 */
	public function tryRegister()
	{
		$registrationStatus = $this->configKey->get(REGISTRATION_KEY);

		if ($registrationStatus === 'Close')
		{
			return redirect('users/register/closed');
		}

		$this->form_validation->set_rules(
			'netid',
			'Net ID',
			array(
				'required',
				'strtolower',
				array(
					'notAlreadyRegistered',
					array($this->user, 'notAlreadyRegistered')
				)
			),
			array(
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
				'integer',
				'greater_than[1000000]'
			)
		);

		$this->form_validation->set_rules(
			'email',
			'E-mail address',
			array(
				'required',
				'trim',
				'valid_email'
			)
		);

		$this->form_validation->set_rules(
			'gender',
			'Gender',
			array(
				'required',
				array(
					'isMaleOrFemale',
					function($gender)
					{
						return in_array($gender, array('male', 'female'));
					}
				)
			),
			array(
				'isMaleOrFemale' => 'Your gender must be male of female.'
			)
		);

		$this->form_validation->set_rules(
			'first',
			'First study',
			array(
				'required',
				array(
					'isYesOrNo',
					function($first)
					{
						return in_array($first, array('yes', 'no'));
					}
				)
			),
			array(
				'isYesOrNo' => 'It is either your first study or not.'
			)
		);

		$this->form_validation->set_rules(
			'english',
			'English mentoraat',
			array(
				'required',
				array(
					'isYesOrNo',
					function($english)
					{
						return in_array($english, array('yes', 'no'));
					}
				)
			),
			array(
				'isYesOrNo' => 'Mentoraat is either in English or not.'
			)
		);

		if ($this->form_validation->run() == FALSE) {
			$this->register();
		}
		else {
			$password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
			$netid = $this->input->post('netid');
			$studentid = $this->input->post('studentid');

			$this->user->register($netid, $password);
			$this->student->insertNewStudent($netid, $studentid,
				$this->input->post('email'),
				$this->input->post('gender'),
				$this->input->post('first'),
				$this->input->post('english')
			);

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
