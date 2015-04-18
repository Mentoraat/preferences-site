<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Model {

    public function existsByNetid($netid)
    {
        if (is_array($netid))
        {
            foreach ($netid as $single_net_id)
            {
                if (!$this->existsByNetid($single_net_id))
                {
                    return FALSE;
                }
            }

            return TRUE;
        }

        $value = $this->db->query('
            SELECT 1
            FROM students
            WHERE netid = ' . $this->db->escape($netid)
        )->first_row();

        return isset($value);
    }

    public function isSameStudent()
    {
        $netid = $this->input->post('netid');
        $studentid = $this->input->post('studentid');

        $value = $this->db->query('
            SELECT 1
            FROM students
            WHERE netid = ' . $this->db->escape($netid) . '
                AND studentid = ' . $this->db->escape($studentid)
        )->first_row();

        return isset($value);
    }

    public function like($like)
    {
        $values = $this->db->query('
            SELECT *
            FROM students
            WHERE netid LIKE "%' . $this->db->escape_like_str($like) . '%"
        ')->result();

        return $values;
    }
}
