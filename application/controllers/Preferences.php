<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Preferences extends Authenticated_Controller {

	/**
	 * Default page that shows the possible preferences of a user.
	 *
	 * @return void
	 */
	public function index($wasSuccess = '')
	{
		$data = array(
			'userid' => $this->user->getUserId(),
			'preferences' => $this->preference->get(),
			'roles' => $this->preference->getRoles(),
			'status' => $this->configKey->get(PREFERENCES_KEY) === 'Close',
			'wasSuccess' => $wasSuccess === 'success',
			'formUrl' => 'preferences/update'
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
		$preferenceStatus = $this->configKey->get(PREFERENCES_KEY);

		if ($preferenceStatus === 'Close')
		{
			return redirect('preferences/index');
		}

		$data = array(
			'userid' => $this->input->post('userid'),
			'role' => $this->input->post('role'),
			'names' => array_filter($this->input->post('names'))
		);
		$this->form_validation->set_data($data);

		$seenNames = array();
		$that = $this;

		$filteredNames = array_filter($this->input->post('names'));
		// If no name was provided, ignore all names validation
		if (!empty($filteredNames))
		{
			$length = NULL;

			$distinctFunction = function ($name) use (&$seenNames, &$that)
			{
				$name = strtolower($name);
				if (in_array($name, $seenNames) || $that->user->isCurrentNetid($name))
				{
					return FALSE;
				}
				else
				{
					$seenNames[] = $name;
					return TRUE;
				}
			};

			$lengthFunction = function ($name) use (&$length, &$that)
			{
				if ($length === NULL)
				{
					$numberOfPreferences = count(array_filter($that->input->post('names')));
					$length = $numberOfPreferences >= MINIMUM_NUMBER_OF_PREFERENCES
						&& $numberOfPreferences <= MAXIMUM_NUMBER_OF_PREFERENCES;
				}

				return $length;
			};

			$existsFunction = function($name) use (&$that)
			{
				$bool = $that->student->existsByNetid(strtolower($name));
				if (!$bool)
				{
					$that->form_validation->set_message('existsByNetid', 'Netid "' . $name . '" could not be found in the database.');
					return FALSE;
				}

				return TRUE;
			};

			$this->form_validation->set_rules(
				'names[]',
				'Names',
				array(
					array(
						'existsByNetid',
						$existsFunction
					),
					array(
						'distinct',
						$distinctFunction
					),
					array(
						'length',
						$lengthFunction
					)
				),
				array(
					'distinct' => 'The provided students contain duplicate/incorrect values.',
					'length' => 'Provide at least ' . MINIMUM_NUMBER_OF_PREFERENCES . ' and at most ' . MAXIMUM_NUMBER_OF_PREFERENCES . ' students'
				)
			);
		}

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

		$sumOfRoles = 0;
		$acceptedRoles = array(
			"Bedrijfsman",
			"Brononderzoeker",
			"Plant",
			"Monitor",
			"Vormer",
			"Voorzitter",
			"Zorgdrager",
			"Groepswerker",
			"Specialist"
		);

		$allRolesFunction = function ($role) use (&$acceptedRoles, &$that)
		{
			if (count($acceptedRoles) > 0)
			{
				$providedRoles = array_keys($that->input->post('role'));

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
		};

		$sumFunction = function ($role) use (&$sumOfRoles, &$that)
		{
			if ($sumOfRoles === 0)
			{
				foreach ($that->input->post('role') as $role)
				{
					$sumOfRoles += ($role > 0);
				}
			}

			return $sumOfRoles === 2;
		};

		$this->form_validation->set_rules(
			'role[]',
			'Role',
			array(
				'required',
				'less_than[25]',
				array(
					'represented',
					$allRolesFunction
				),
				array(
					'sum',
					$sumFunction
				)
			),
			array(
				'required' => 'You must provide all numbers for all roles.',
				'less_than' => 'Role percentages must be less than 25.',
				'sum' => 'You must provide 2 role percentages.',
				'represented' => 'Not all roles are represented.'
			)
		);

		if ($this->form_validation->run() === FALSE)
		{
			$data = array(
				'userid' => $this->user->getUserId(),
				'preferences' => $this->preference->get(),
				'roles' => $this->preference->getRoles(),
				'status' => $this->configKey->get(PREFERENCES_KEY) === 'Close',
				'wasSuccess' => false
			);

			$this->load->page('preferences/index', $data);
		}
		else
		{
			$this->preference->update($seenNames, $this->input->post('role'));

			return redirect('preferences/index/success');
		}
	}

}
