<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Preference extends CI_Model {

    /**
     * Update the preferences of the current user.
     *
     * @param  array $names The netids of the preferred students.
     * @return void
     */
    public function update($names)
    {
        $query = 'INSERT INTO preferences (studentid, prefers_studentid) VALUES ';

        foreach ($names as $name)
        {
            $query .= '(' . $this->db->escape($this->user->getNetid()) . ',' . $this->db->escape($name) . '),';
        }

        $this->db->query(rtrim($query, ','));
    }
}
