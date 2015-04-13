<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Preferences extends Authenticated_Controller {

	/**
	 * Default page that shows the possible preferences of a user.
	 *
	 * @return void
	 */
	public function index()
	{
		$data = array(
			'userid' => $this->user->getUserId(),
			'preferences' => $this->preference->get(),
			'roles' => $this->preference->getRoles()
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

		$sumOfRoles = 0;
		$acceptedRoles = array(
            'Analyst',
            'Chairman',
            'Completer',
            'Driver',
            'Executive',
            'Expert',
            'Explorer',
            'Innovater',
            'TeamPlayer'
        );

		$this->form_validation->set_rules(
			'role[]',
			'Role',
			array(
				'required',
				'greater_than[1]',
				'less_than[25]',
				array(
					'represented',
					function ($role) use (&$acceptedRoles)
					{
						if (count($acceptedRoles) > 0)
						{
							$providedRoles = array_keys($this->input->post('role'));

							if (count($providedRoles) !== count($acceptedRoles))
							{
								return FALSE;
							}

							$acceptedRoles = array_diff(
								$providedRoles,
								$acceptedRoles
							);
						}

						return count($acceptedRoles) === 0;
					}
				),
				array(
					'sum',
					function ($role) use (&$sumOfRoles)
					{
						if ($sumOfRoles === 0)
						{
							foreach ($this->input->post('role') as $role)
							{
								$sumOfRoles += $role;
							}
						}

						return $sumOfRoles === 100;
					}
				)
			),
			array(
				'required' => 'You must provide all numbers for all roles.',
				'greater_than' => 'Role percentages must be greater than 1.',
				'less_than' => 'Role percentages must be less than 25.',
				'sum' => 'The role percentages must sum up to 100.',
				'represented' => 'Not all roles are represented.'
			)
		);

		if ($this->form_validation->run() === FALSE)
		{
			$this->load->page('preferences/index');
		}
		else
		{
			$this->preference->update($seenNames, $this->input->post('role'));

			return redirect('preferences/index/success');
		}
	}

}
