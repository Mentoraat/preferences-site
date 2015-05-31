<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends Admin_Controller {

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
