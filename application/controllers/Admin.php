<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends Admin_Controller {

	public function dump()
	{
		$delimit = function($p, $delimiter) {
			$output = '';
			foreach ($p as $row) {
				$output .= $row . $delimiter;
			}
			return rtrim($output, $delimiter);
		};
		$csvit = function($p) use ($delimit) {
			return $delimit($p, ',');
		};
		$rowit = function($p) use ($delimit) {
			return $delimit($p, '\\n');
		};
		print_r($rowit(array_map($csvit,
		$this->db->query('
			SELECT *
			FROM students
		')->result_array())));
print_r('PREFERENCES');
	print_r($rowit(array_map($csvit, $this->db->query('
	SELECT *
	FROM preferences
	')->result_array())));
print_r('TEAM_ROLES');
	print_r($rowit(array_map($csvit, $this->db->query('
	SELECT *
	FROM team_roles
	')->result_array())));
}

    public function index()
    {
        /*
            The total number of students registered.
         */
        $totalStudents = $this->db->query('
        SELECT COUNT(1) AS count
        FROM students
        ')->row()->count;

        /*
            The list of students that don't have any preferences yet.
         */
        $students = $this->db->query('
        SELECT SQL_CALC_FOUND_ROWS *
        FROM students
        WHERE NOT EXISTS (
                SELECT 1
                FROM preferences
                WHERE studentid = students.netid
            )
        LIMIT 25
        ')->result();

        $total = $this->db->query('
        SELECT FOUND_ROWS() AS `count`
        ')->row();

        /*
            The registration status, inverted for $newStatus.
         */
        $registrationStatus = $this->configKey->get(REGISTRATION_KEY);

        $newRegistrationStatus = $registrationStatus === 'Open' ? 'Close' : 'Open';

        /*
            The preference process status, inverted for $newStatus.
         */
        $preferencesStatus = $this->configKey->get(PREFERENCES_KEY);

        $newPreferenceStatus = $preferencesStatus === 'Open' ? 'Close' : 'Open';

        $data = array(
            'registrationStatus' => $registrationStatus,
            'preferencesStatus' => $preferencesStatus,
            'newRegistrationStatus' => $newRegistrationStatus,
            'newPreferenceStatus' => $newPreferenceStatus,
            'students' => $students,
            'totalStudents' => $totalStudents,
            'total' => $total->count
        );

        $this->load->page('admin/index', $data);
    }

		public function registeruser()
		{
			$this->load->page('users/register', array('postUrl' => 'admin/tryUserRegister', 'status' => '', 'message' => 'NOTE: THIS PAGE DOES NOT HAVE ANY VALIDATION. BE CAREFUL'));
		}

		public function tryUserRegister()
		{
			$password = 'NO_PASSWORD_SET';
			$netid = $this->input->post('netid');
			$studentid = $this->input->post('studentid');

			$this->user->register($netid, $password);
			$this->student->insertNewStudent($netid, $studentid,
				$this->input->post('email'),
				$this->input->post('gender'),
				$this->input->post('first'),
				$this->input->post('english')
			);

			return redirect('admin/registeruser');
		}

		public function setpreferences($userId=0)
		{
			if ($userId === 0)
			{
				$this->load->page('admin/setpreferences');
				return;
			}

			$data = array(
				'wasSuccess' => false,
				'userId' => $this->user->getUserIdFromNetId($userId),
				'formUrl' => 'admin/setpreferencesforuser'
			);
			$this->load->page('preferences/index', $data);
		}

		public function setpreferencesforuser()
		{
			$this->preference->update(array_filter($this->input->post('names')), $this->input->post('role'));

			return redirect('admin/setpreferences');
		}

    public function registration($newStatus)
    {
        $this->db->query('
        UPDATE config
        SET value = ' . $this->db->escape($newStatus) . '
        WHERE `key` = ' . $this->db->escape(REGISTRATION_KEY) . '
        ');

        return redirect('admin/index');
    }

    public function preferences($newStatus)
    {
        $this->db->query('
        UPDATE config
        SET value = ' . $this->db->escape($newStatus) . '
        WHERE `key` = ' . $this->db->escape(PREFERENCES_KEY) . '
        ');

        return redirect('admin/index');
    }

}
