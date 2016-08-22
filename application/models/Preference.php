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
        WHERE studentid = ' . $this->db->escape($this->user->getNetid()) . '
        ORDER BY `order`
        ')->result_array();

        return array_map(function($p) {
            return $p['prefers_studentid'];
        }, $preferences);
    }

    /**
     * Get the roles for this user.
     *
     * @return array The roles that this user provided.
     */
    public function getRoles()
    {
        $roles = $this->db->query('
        SELECT role, percentage
        FROM team_roles
        WHERE netid = ' . $this->db->escape($this->user->getNetid()) . '
        ')->result_array();

        return array_reduce($roles, function($result, $p) {
            $result[$p['role']] = $p['percentage'];
            return $result;
        }, array());
    }

    /**
     * Update the preferences of the current user.
     *
     * @param  array $names The netids of the preferred students.
     * @return void
     */
    public function update($names, $roles)
    {
        $netid = $this->user->getNetid();

        if (!empty($names))
        {
          $this->db->query('
          DELETE FROM preferences
          WHERE studentid = ' . $this->db->escape($netid)
          );

          $query = '
          INSERT INTO preferences (studentid, prefers_studentid, `order`)
          VALUES ';

          $i = 1;
          foreach ($names as $name)
          {
              $query .= '(' . $this->db->escape($netid) . ',' . $this->db->escape($name) . ',' . $i++ . '),';
          }

          $this->db->query(rtrim($query, ','));
        }

        $this->db->query('
        DELETE FROM team_roles
        WHERE netid = ' . $this->db->escape($netid)
        );

        $query = '
        INSERT INTO team_roles (netid, role, percentage)
        VALUES ';

        foreach ($roles as $role => $percentage)
        {
            $query .= '(' . $this->db->escape($netid) . ',' . $this->db->escape($role) . ',' . $percentage . '),';
        }

        $this->db->query(rtrim($query, ','));
    }
}
