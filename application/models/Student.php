<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Model {

    /**
     * Check whether the provided netID exists in the database.
     *
     * @param string $netid The netID to check for.
     * @return boolean Whether the netID is in the database.
     */
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

    public function insertNewStudent($netid, $studentid)
    {
        $this->db->query('
        INSERT INTO students (netid, studentid)
        VALUES (
            ' . $this->db->escape($netid) . ',
            ' . $this->db->escape($studentid) . '
            )
        ');
    }

    /**
     * Retrieve all students that have a netid like the provided netID.
     *
     * @param  string $like the netID to search for.
     * @return array       all netids that contain $like.
     */
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
