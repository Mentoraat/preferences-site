<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Preference extends CI_Model {

    /**
     * Get the preferences of the current user.
     * @return array The net ids of the preferred students.
     */
    public function get()
    {
        $preferences = $this->db->query('
        SELECT prefers_studentid
        FROM preferences
        WHERE studentid = ' . $this->db->escape($this->user->getNetid())
        )->result_array();

        return array_map(function($p) {
            return $p['prefers_studentid'];
        }, $preferences);
    }

    /**
     * Update the preferences of the current user.
     *
     * @param  array $names The netids of the preferred students.
     * @return void
     */
    public function update($names)
    {
        $netid = $this->user->getNetid();

        $this->db->query('
        DELETE FROM preferences
        WHERE studentid = ' . $this->db->escape($netid)
        );

        $query = 'INSERT INTO preferences (studentid, prefers_studentid) VALUES ';

        foreach ($names as $name)
        {
            $query .= '(' . $this->db->escape($netid) . ',' . $this->db->escape($name) . '),';
        }

        $this->db->query(rtrim($query, ','));
    }
}
